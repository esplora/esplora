<?php

declare(strict_types=1);

namespace Esplora\Tracker\Presenters;

use Esplora\Tracker\Presenter;
use Illuminate\Database\Eloquent\Model;

class VisitorBrowsersPresenter extends Presenter
{
    public function present(Model $model): array
    {
        return [
           'browser' => $model->browser,
           'count' => (int)$model->count
        ];
    }
}
