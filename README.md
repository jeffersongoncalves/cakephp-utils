# cakephp-utils

[![Buy Me A Coffee](https://img.shields.io/badge/Buy%20Me%20A%20Coffee-support-FFDD00?style=flat-square&logo=buy-me-a-coffee&logoColor=black)](https://buymeacoffee.com/jeffersongoncalves)

CakePHP 3 helper classes for building server-side REST API / DataTables-style responses:
rendering action links (view/edit/delete/pdf/...) as HTML, wrapping JS callbacks for
DataTables configuration, and a [League Fractal](https://fractal.thephpleague.com/)
transformer base that gains access to all of the above.

This is the standalone-class sibling of
[cakephp-utility](https://github.com/jeffersongoncalves/cakephp-utility): same API under the
`JeffersonSimaoGoncalves\Utils` namespace instead of `JeffersonSimaoGoncalves\Utility`, with no
`Plugin` class — nothing to load via `Plugin::load()`, just autoloaded PHP classes.

## Requirements

- PHP >=7.0
- `league/fractal` >=0.13.0
- `airmanbzh/php-html-generator` ^1.0

## Installation

```bash
composer require jeffersonsimaogoncalves/cakephp-utils
```

## Usage

### TableUtility — DataTables-ready response

```php
use JeffersonSimaoGoncalves\Utils\TableUtility;

$table = new TableUtility();
$table->columns = $columns;
$table->order = [['0', 'asc']];
$table->url = '/users/index.json';
$table->data = $rows;
$table->setCount(count($rows), $totalCount);

return $this->response->withStringBody(json_encode($table->getOptions()));
```

`getOptions()` returns `data`, `deferLoading`, `columns`, `order` and (when `url` is set)
an `ajax` key, ready to be consumed by a DataTables front-end configuration.

### CallbackTrait — JS function references in JSON

```php
use JeffersonSimaoGoncalves\Utils\CallbackTrait;

class UsersController extends AppController
{
    use CallbackTrait;

    public function index()
    {
        $renderCallback = $this->callback('renderStatusColumn', ['active']);
        // JSON-serializable; encode it inside your DataTables config and resolve the
        // placeholder afterwards with CallbackFunction::resolve($json)
    }
}
```

### Links — render action links as HTML

```php
use JeffersonSimaoGoncalves\Utils\Links\RenderLink;

// Configure the CSS classes used per link type (usually in config/app.php or bootstrap.php)
Configure::write('JeffersonSimaoGoncalves/Utils.RenderLink.linkEdit', [
    'classLink' => 'btn btn-sm btn-primary',
    'classIcon' => 'fa fa-pencil',
]);

class UsersTransformer extends \JeffersonSimaoGoncalves\Utils\Model\Transformer\LinkBaseTransformer
{
    public function transform($user)
    {
        $link = new RenderLink();
        $link->linkEdit('/users/edit/' . $user->id);

        return [
            'id' => $user->id,
            'actions' => $this->renderLink($link),
        ];
    }
}
```

`RenderLink` supports `linkView()`, `linkEdit()`, `linkAdd()`, `linkBack()`, `linkPdf()`,
`linkImage()`, `linkAccept()` and a generic `link()`. Each type reads its `classLink`/`classIcon`
from `Configure`, keyed by `JeffersonSimaoGoncalves/Utils.RenderLink.<method>`.

### Forms — status toggle / delete with confirmation

```php
use JeffersonSimaoGoncalves\Utils\Links\RenderForm;

Configure::write('JeffersonSimaoGoncalves/Utils.RenderForm.formDelete', [
    'classLink' => 'btn btn-sm btn-danger',
    'classIcon' => 'fa fa-trash',
]);

$form = new RenderForm();
$form->formDelete('/users/delete/' . $user->id, $user->name);

echo $this->renderForm($form); // hidden POST form + confirm() dialog on click
```

## Credits

- [Jèfferson Simão Gonçalves](https://github.com/jeffersonsimaogoncalves)
