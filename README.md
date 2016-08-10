CheckHealth Pimcore Plugin
================================================
    
Developer info: [Pimcore at basilicom](http://basilicom.de/en/pimcore)

## Synopsis

This Pimcore http://www.pimcore.org plugin provides a controller/action
where upon access a couple of checks regarding system health are performed.
Output is SUCCESS or FAILURE - this is suitable for continuous monitoring
via StatusCake, Pingdom or a similar service.

## Code Example / Method of Operation

After installing the plugin, configuration options are exposed via the 
configure button in the Extension Manager.

More checks can be added by new <check></check> blocks listing additional
classes implementing the check() method of the CheckInterface.
If a check fails, an exception must be thrown. Messages of CheckExceptions
are publically visible. All other exceptions are logged via the Pimcore
ApplicationLogger and identifiable via an unique id.

The URL to trigger the check is:

    [domain]/plugin/CheckHealth/check/status

## Motivation

Services like Pingdom and StatusCake should be used by detecting a specific
success state instead of looking for closing body tags, status codes or similar
indications. A dedicated list of checks helps ensuring a fully functioning
web system.

## Installation

Add "basilicom-pimcore/check-health" as a requirement to the composer.json 
in the toplevel directory of your Pimcore installation. Then enable and install 
the plugin in Pimcore Extension Manager (under Extras > Extensions)

Note: The xml config file property "enabled" must be set to "1" in order to
enable the checks.

Example:

    {
        "require": {
            "basilicom-pimcore-plugin/protected-admin": ">=1.0.0"
        }
    }

## Contributors

* Christoph Luehr christoph.luehr@basilicom.de

## License

* GNU General Public License version 3 (GPLv3)
