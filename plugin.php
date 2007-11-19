<?php
add_theme_pages('timeline', 'admin');

class Timeline
{
    public $data = array();
    public $startDate;
    public $xml;
    public $javascript;
    
    public function __construct()
    {
        $this->setData();
        
        $this->setStartDate();
        
        $this->setMonthFrequencies();
       
        $this->setXml();
        $this->writeXmlFile();
        
        $this->setJavascript();
    }
    
    private function setData()
    {
        $db = Doctrine_Manager::connection();
        $sql = 'SELECT `id`, `date`, `title`, `description` 
        FROM `items` 
        WHERE `date` REGEXP "[[:digit:]]{4}-[[:digit:]]{2}-[[:digit:]]{2}"
        AND `date` NOT REGEXP "^0000-00-00$" 
        AND `date` NOT REGEXP "00-00$" 
        AND `date` NOT REGEXP "00$"';
        $this->data = $db->fetchAll($sql);
    }
    
    private function setStartDate()
    {
        $years = array();
        foreach ($this->data as $data) {
            $d = date_parse($data['date']);
            $years[] = $d['year'];
        }
        // get the average year. not optimal but it works for now. median may be better
        $this->startDate = $this->formatDate(round(array_sum($years) / count($years)).'-01-01');
    }
    
    private function setMonthFrequencies()
    {
        $months = array();
        
        foreach ($this->data as $data) {
            $d = date_parse($data['date']);
            $months[] = $d['year'].'-'.str_pad($d['month'], 2, '0', STR_PAD_LEFT);
        }
        
        $monthFreq = array_count_values($months);
        
        $this->monthFreq = array_filter($monthFreq, array($this, 'filterFrequency'));
    }
        
    private function filterFrequency($freq)
    {
        if ($freq > 10) return true; 
    }
    
    private function setXml()
    {
        $xml = '
<data>';
        foreach ($this->data as $data) {
            $start = $this->formatDate($data['date']);
            $xml .= '
    <event start="'.$start.'"
           title="'.htmlspecialchars(strip_tags($data['title']), ENT_QUOTES).'">'.htmlspecialchars(substr($data['description'], 0, 200).'...<br /><a href="'.uri('items/show/'.$data['id']).'" target="_blank">[go to item]</a>').'</event>';
        }
        $xml .= '
</data>';
        
        $this->xml = $xml;
    }
    
    private function formatDate($date)
    {
        $d = date_parse($date);
        
        $month  = date('M', mktime(0, 0, 0, $d['month']));
        $day    = date('d', mktime(0, 0, 0, 0, $d['day']));
        $year   = $d['year'];
        $hour   = date('H', mktime($d['hour']));
        $minute = date('i', mktime(0, $d['minute']));
        $second = date('s', mktime(0, 0, $d['second']));
        $gmt    = 'GMT';
        
        return $month.' '.$day.' '.$year.' '.$hour.':'.$minute.':'.$second.' '.$gmt;
    }
    
    private function writeXmlFile()
    {
        $filename = PLUGIN_DIR . DIRECTORY_SEPARATOR . 'Timeline' . DIRECTORY_SEPARATOR . 'data.xml'; //'/websites/chnm/staff/jsafley/omeka/plugins/Timeline/data.xml';
        if (is_writable($filename)) {
            if (!$handle = fopen($filename, 'w')) {
                exit('fopen() error');
            }
            if (fwrite($handle, $this->xml) === FALSE) {
                exit('fwrite() error');
            }
            fclose($handle);
        } else {
            exit('is_writable() error');
        }
    }
    
    private function setJavascript()
    {
        $js = '
var tl;
function onLoad() {
  var eventSource = new Timeline.DefaultEventSource();
  var bandInfos = [
    Timeline.createHotZoneBandInfo({
        zones: [';
        foreach ($this->monthFreq as $month => $freq) {
            $d = date_parse($month);
            
            $zoneStartYear  = $d['year'];
            $zoneStartMonth = str_pad(($d['month']), 2, '0', STR_PAD_LEFT);
            $zoneStartDay   = '01';
            $zoneStart = $this->formatDate($zoneStartYear.$zoneStartMonth.$zoneStartDay);
            
            if ($d['month'] == '12') {
                $zoneStartYear  = $d['year'] + 1;
                $zoneStartMonth = '01';
                $zoneStartDay   = '01';
            } else {
                $zoneStartYear  = $d['year'];
                $zoneStartMonth = str_pad(($d['month'] + 1), 2, '0', STR_PAD_LEFT);
                $zoneStartDay   = '01';
            }
            $zoneEnd = $this->formatDate($zoneStartYear.$zoneStartMonth.$zoneStartDay);
            
            $zoneUnit = $freq > 20 ? 'Timeline.DateTime.DAY' : 'Timeline.DateTime.WEEK';
            
            $js .= '
            {   start:    "'.$zoneStart.'",
                end:      "'.$zoneEnd.'",
                magnify:  10,
                unit:     '.$zoneUnit.'
            },';
        }
        $js .= '],
        eventSource:    eventSource,
        date:           "'.$this->startDate.'",
        width:          "70%", 
        intervalUnit:   Timeline.DateTime.MONTH, 
        intervalPixels: 150
    }),
    Timeline.createHotZoneBandInfo({
        zones: [],
        showEventText:  false,
        trackHeight:    0.5,
        trackGap:       0.2,
        eventSource:    eventSource,
        date:           "'.$this->startDate.'",
        width:          "30%", 
        intervalUnit:   Timeline.DateTime.YEAR, 
        intervalPixels: 200
    })
  ];
  bandInfos[1].syncWith = 0;
  bandInfos[1].highlight = true;
  bandInfos[1].eventPainter.setLayout(bandInfos[0].eventPainter.getLayout());
  
  tl = Timeline.create(document.getElementById("my-timeline"), bandInfos);
  Timeline.loadXML("'.uri('timeline_data').'", function(xml, url) { eventSource.loadXML(xml, url); });
}

var resizeTimerID = null;
function onResize() {
    if (resizeTimerID == null) {
        resizeTimerID = window.setTimeout(function() {
            resizeTimerID = null;
            tl.layout();
        }, 500);
    }
}';
        $this->javascript = $js;
    }
}

?>