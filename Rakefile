require 'peach'
require 'fileutils'
require 'rake/packagetask'
require 'tempfile'
require 'inifile'

task :default => [
  'php:unit',
]

namespace :php do
  desc 'Run unit tests.'
  task :unit, [:filter] do |t, args|
    filter_by = args[:filter]
    if filter_by.nil? then
      filter_params = ""
    else
      filter_params = " --filter #{filter_by}"
    end

    sh %{cd tests/ && phpunit --configuration phpunit.xml #{filter_params}}
  end
end

class PackageTask < Rake::PackageTask
  def package_dir_path()
    "#{package_dir}/#{@name}"
  end
  def package_name
    @name
  end

  def basename
    @version ? "#{@name}-#{@version}" : @name
  end

  def tar_bz2_file
    "#{basename}.tar.bz2"
  end
  def tar_gz_file
    "#{basename}.tar.gz"
  end
  def tgz_file
    "#{basename}.tgz"
  end
  def zip_file
    "#{basename}.zip"
  end
  def get_version()
    ini = IniFile.load('plugin.ini')
    section = ini['info']
    version = section['version']
    "#{version}"
  end
end

PackageTask.new('NeatlineTime') do |p|
  p.version     = p.get_version()
  p.need_tar_gz = true
  p.need_zip    = true

  p.package_files.include('README.md')
  p.package_files.include('LICENSE')
  p.package_files.include('plugin.*')
  p.package_files.include('**/*.php')
  p.package_files.include('languages/*')
  p.package_files.include('views/**/*.css')
  p.package_files.include('views/**/*.gif')
  p.package_files.include('views/**/*.js')
  p.package_files.include('views/**/*.php')
  p.package_files.include('views/**/*.png')
  p.package_files.exclude('pkg')
end

desc 'Updates POT files.'
task :update_pot do
  files = (Dir["*.{php,phtml}"] + Dir["**/*.{php,phtml}"]).select do |p|
    ! (p.start_with?("tests/") || p.start_with?("pkg/"))
  end
  puts files.inspect
  lang_dir = "languages"
  core_pot = "../../application/languages/Omeka.pot"
  pot_file = "#{lang_dir}/template.pot"
  pot_base = "#{lang_dir}/template.base.pot"
  pot_temp = Tempfile.new(".pot")
  pot_temp.close
  pot_duplicates = Tempfile.new("-duplicates.pot")
  pot_duplicates.close

  sh %{xgettext -L php --from-code=utf-8 -k__ --flag=__:1:pass-php-format --omit-header -F -o #{pot_temp.path} #{files.join(' ')}}


  sh %{msgcomm --omit-header -o #{pot_duplicates.path} #{pot_temp.path} #{core_pot}}

  sh %{msgcomm -u -o #{pot_temp.path} #{pot_temp.path} #{pot_duplicates.path}}

  sh %{msgcat -o #{pot_file} #{pot_base} #{pot_temp.path}}

  pot_temp.close(true)
  pot_duplicates.close(true)
end

desc 'Builds MO files from existing PO files.'
task :build_mo do
  files = Dir["languages/*.{po}"]

  files.pmap do |filename|
    targetfile = filename.sub(/\.po$/,'.mo')
    sh %{msgfmt -o #{targetfile} #{filename}}
  end
end
