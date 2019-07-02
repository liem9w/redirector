# Redirector plugin for Craft CMS 3.x

Automatically generates Retour redirects from a entry URI dump

## Installation

Install via Composer with the following command:

```
composer require morsekode/redirector
```

Enable the plugin via CraftCMS control panel.

## Usage

### Entry Dump Format

```
[{
    "id": 123,
    "uri": "my/page/path"
},
{
    ...
},
{
    ...
}]
```

### Run Build Command

Enter the following command in your terminal:

```
cd MyProject
./craft redirector/build myentryuris.json
```