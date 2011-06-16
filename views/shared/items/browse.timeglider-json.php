[
{
    "id": "items-browse",
    "title": <?php echo js_escape(settings('site_title') . "Items | Timeline"); ?>,
    "focus_date": "2011-06-01 12:00:00",
    "initial_zoom": "40",
    "events": [
    <?php while ( loop_items() ) : ?>
        <?php echo get_timeline_json_for_item(); ?>
    <?php endwhile; ?>
    ]
}
]