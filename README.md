
# De.SWebhosting.Bootstrap - Frontend utilites for TYPO3 Flow

This package provides some view helpers and utility functions
for TYPO3 Flow that can make Frontend development easier.

The default ouput of the view helpers is optimized to work
with the [Bootstrap] (http://getbootstrap.com) framework
(therefore the name) but can be useful with other frameworks
or custom styles as well.

## Components

This is a brief overview over the components this package provides.

### View helpers

This package comes with a bunch of view helpers that hopefully make
your life a bit easier. Please remember to register the namespace
if you want to use them:

```
{namespace bs=De\SWebhosting\Bootstrap\ViewHelpers}
```
 
Here is a brief overview about the available view helpers. You can find
detailed descriptions of each of them in the ```Documentation/ViewHelpers```
folder.
 
Name                             | Purpose
-------------------------------- | -------
form.inlineHelpOrErrors          | Displays [inline help] (http://getbootstrap.com/css/#forms-help-text) or error messages for a form element.
form.validatedControlGroup       | Displays a form group with [classes depending on validation errors] (http://getbootstrap.com/css/#forms-control-validation).
format.trimWhiteSpaceBetweenHtml | Removes all whitespace between HTML elements.
javaScript.append                | Appends content to the JavaScript container, see section "JavaScript container" below.
javaScript.render                | Renders contents of the JavaScript container, see section "JavaScript container" below.
resource.collectionUri           | Renders the public URI to a resource within a custom collection. Requires [TYPO3 Flow patch] (https://review.typo3.org/#/c/37686/)!
widget.autocomplete              | A widget to enable autocompletion for an input field. Requires [jQuery UI] (https://jqueryui.com/autocomplete/)!
widget.paginate                  | The paginate widget with modified classes to work with Bootstrap.
menuItem                         | A HTML container (div) that changes its classes depending on the active controller / actions.

### JavaScript container

The idea behind the JavaScript container is to provide a central container
where components can register JavaScript code they need which can then be
rendered by template designers using a view helper.

You can either add JavaScript code to the container or a file that
should be included with a script tag.

It is also possible to manage multipe sections that should be rendered
in different places (e.g. header and footer).

### Resource handling

This package provides the ```\De\SWebhosting\Bootstrap\Resource\ReadOnlyDirectoryStorage```.
This storage can be used to publish any local directory together with
the ```\TYPO3\Flow\Resource\Target\FileSystemSymlinkTarget```.

Create your custom collection to publish a Frontend library that you have loaded
with composer and use the ```resource.collectionUri`` view helper to retrieve
the matching URL to include the library in your layouts.


### Partials

This package currently provides the following partials:

Partial       | Purpose
--------------| -------
ErrorList     | This partial can be used for debugging purposes. It prints all validations errors in a flat list.
FlashMessages | This partial renders all available flash messages in Bootstrap compatible containers / classes.

To use those partials in your application you need to add the partials path
of the De.SWebhosting.Bootstrap package in the ```Views.yml``` configuration
file.

