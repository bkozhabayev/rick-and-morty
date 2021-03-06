<?php

namespace App\Services\v1;

use App\Models\Character;
use App\Services\v1\BaseService;
use App\Http\Requests\CharacterRequest;
use Illuminate\Support\Facades\Storage;
use App\Repositories\CharacterRepository;

class CharacterService extends BaseService
{
    protected $repo;

    public function __construct()
    {
        $this->repo = new CharacterRepository;
    }

    public function indexPaginate($params)
    {
        return $this->result($this->repo->indexPaginate($params));
    }

    public function get($id)
    {
        $model = $this->repo->get($id);
        if (is_null($model)) {
            return $this->errNotFound('Персонаж не найден');
        }
        return $this->result($model);
    }

    public function indexCharacterEpisodes($character_id, $params)
    {
        $model = $this->repo->get($character_id);
        if (is_null($model)) {
            return $this->errNotFound('Персонаж не найден');
        }

        $collection = $this->repo->indexCharacterEpisodes($character_id, $params);
        return $this->result($collection);
    }

    public function store($params)
    {
        $model = $this->repo->store($params);
        if (is_null($model)) {
            return $this->errService('Ошибка при создании персонажа');
        }
        return $this->ok('Персонаж сохранен');
    }


    public function update($params, $id)
    {
        /**
         * Существует ли модель?
         */
        $model = $this->repo->get($id);
        if (is_null($model)) {
            return $this->errNotFound('Не найден персонаж для обновления');
        }

        /**
         * Есть ли уже персонаж с таким именем? 
         */
        if ($this->repo->existsName($params['name'], $id)) {
            return $this->errValidate('Другой персонаж с таким именем уже существует');
        }

        $this->repo->update($params, $id);
        return $this->ok('Персонаж обновлен');
    }

    public function destroy($id)
    {
        // Проверка персонажа на сушествование 
        $model = $this->repo->get($id);
        if (is_null($model)) {
            return $this->errNotFound('Не найден персонаж для удаления');
        }

        $this->repo->destroy($id);
        return $this->ok('Персонаж удален');
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
        // Проверить если у персонажа есть картинка
        $check = $this->repo->getImage($params['id']);
        if (is_null($check)) {
            return $this->errNotFound('У персонажа нет картинки');
        }

        $this->repo->deleteImage($params['id']);
        return $this->ok('Картинка удалена');
    }
}
