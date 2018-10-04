# SilverStripe GridfieldToggleVisibility

Simple experimental Module to provide Gridfeld Actions to easy toggle a Boolean Field (eg "Hidden", or "Active")

Private project, no help/support provided

## Requirements

* SilverStripe ^4.1


## Installation

- Install the module via Composer
  ```
  composer require silverstripe-gridfieldtogglevisibility
  ```

## Requirements

A boolean field on the target object, eg. "Hidden", "Active", "Visible", etc 

## Configuration


If you use a "negative field" (e.g. "Hidden", "Hide") use **GridFieldToggleHiddenAction**

```
# as param use "Hidden", "Hide" or wahtever your boolean field is named
# defaults to "Hidden" if no param is provided
$MyGridfieldConfig->addComponent(new GridFieldToggleHiddenAction('Hidden'));

```

If you use a "positive field" (e.g. "Active", "Visible") use **GridFieldToggleActiveAction**

```
# as param use "Active", "Vidible" or wahtever your boolean field is named
# defaults to "Active" if no param is provided
$MyGridfieldConfig->addComponent(new GridFieldToggleActiveAction('Active'));

```
****