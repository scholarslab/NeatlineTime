
# Release Checklist

The instructions assume that you're releasing from within a `NeatlineTime`
plugin directory within a working Omeka instance.

1. `VERSION=42.0.13` — We'll use this value later.
1. `git flow release start $VERSION`
1. Bump the version number by editing:
   * `plugin.ini`
1. `git commit`
1. Update i18n:
   * `tx pull --all`
   * `rake update_pot build_mo` (if there are new translations)
   * `git commit` (if there are new translations)
1. Make sure there aren't any extraneous files lying around.
1. `rake package`
1. quick check the zip
1. test the zip
1. `git flow release finish $VERSION`
1. `git push --all`
1. `git push --tags`
1. upload the zip to http://omeka.org/add-ons/plugins/.

