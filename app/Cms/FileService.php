<?php

namespace App\Cms;

use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Image;
use Str;

class FileService
{
    const UPLOAD_PATH_PREFIX = 'public/upload/';

    const DATABASE_SRC_PREFIX = 'storage/upload/';

    public function save($file, $model, $title = 'file')
    {
        // This service can upload both single and array of files
        $gallery = $file;
        if (!is_array($file)) {
            $gallery = [$file];
        }
        foreach ($gallery as $file) {
            $modelName = class_basename($model);
            $modelNameSlug = Str::kebab($modelName);
            $fileableType = config('cms.config.models_namespace') . $modelName;
            $fileableId = $model->id;
            $size = $file->getSize();
            $mimeType = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();
            $randomCode = rand(1000000, 9999999);
            $fileName = $title . '-' . $randomCode . '.' . $extension;

            $uploadFolderPath = self::UPLOAD_PATH_PREFIX . $modelNameSlug . '/' . $fileableId . '/';
            $uploadFolderSrc = self::DATABASE_SRC_PREFIX . $modelNameSlug . '/' . $fileableId;
            $src = asset($uploadFolderSrc . '/' . $fileName);
            Storage::putFileAs($uploadFolderPath, $file, $fileName);

            // save thumbnail if its image
            $thumbnailSrc = null;
            if (strpos($mimeType, 'image') === 0) {
                $thumbnailFileName = $title . '-' . $randomCode . '-' . 'thumbnail' . '.' . $extension;
                $thumbnailSrc = asset($uploadFolderSrc . '/' . $thumbnailFileName);
                $intervationImage = Image::make($file);
                $intervationImage->resize(null, 200, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $intervationUploadPath = storage_path('app/' . $uploadFolderPath);
                $intervationImage->orientate();
                $intervationImage->save($intervationUploadPath . $thumbnailFileName, 100);
            }
            // save file model record
            $fileModelData = [
                'title' => $title,
                'extension' => $extension,
                'file_name' => $fileName,
                'mime_type' => $mimeType,
                'size' => $size,
                'src' => $src,
                'src_thumbnail' => $thumbnailSrc,
                'fileable_type' => $fileableType,
                'fileable_id' => $fileableId,
            ];

            $column = collect($model->getColumns())->where('name', $title)->first();
            if (isset($column['file_multiple']) && $column['file_multiple'] === true) {
                File::updateOrCreate($fileModelData);
            } else {
                // for single file upload this 3 columns is unique.
                File::updateOrCreate(
                    [
                        'title' => $title,
                        'fileableId' => $fileableId,
                        'fileableType' => $fileableType,
                    ],
                    $fileModelData
                );
            }
        }
    }
}
