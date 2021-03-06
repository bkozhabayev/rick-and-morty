<?php

namespace App\Services\v1;

use Illuminate\Support\Facades\Storage;
use App\Repositories\LocationRepository;

class LocationService extends BaseService
{
    protected $repo;
    public function __construct()
    {
        $this->repo = new LocationRepository();
    }

    public function index($params)
    {
        $collection = $this->repo->indexPaginate($params);

        return $this->result($collection);
    }

    public function store($params)
    {
        $model = $this->repo->store($params);

        if (is_null($model)) {
            return $this->errService('Ошибка при создании локации');
        }

        return $this->ok('Локация создана');
    }

    public function get($id)
    {
        $model = $this->repo->get($id);

        if (is_null($model)) {
            return $this->errNotFound('Локация не найдено');
        }

        return $this->result($model);
    }

    public function update($params, $id)
    {
        /**
         * Существует ли локация по ID
         */
        $location = $this->repo->get($id);
        if (is_null($location)) {
            return $this->errNotFound('Локация для обновляения не найдено');
        }

        $this->repo->update($params, $id);
        return $this->ok("Локация обновлена");
    }

    public function destroy($id)
    {
        /**
         * Существует ли локация по ID
         */
        $location = $this->repo->get($id);
        if (is_null($location)) {
            return $this->errNotFound('Локация для удаления не найдено');
        }

        $this->repo->destroy($id);
        return $this->ok("Локация удалена");
    }

    public function setImage($params)
    {
        $path = $params['image']->store('images');
        if (Storage::missing($path)) {
            return $this->errService('Ошибка сохранения картинки');
        }

        $model = $this->repo->setImage($params['id'], $path);
        return $this->result($model);
    }

    public function deleteImage($params)
    {
        $check = $this->repo->getImage($params['id']);
        if (is_null($check)) {
            return $this->errNotFound('У локации нет картинки');
        }

        $this->repo->deleteImage($params['id']);
        return $this->ok('Картинка удалена');
    }
}
