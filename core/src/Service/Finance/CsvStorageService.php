<?php
declare(strict_types = 1);
namespace App\Service\Finance;

use App\Exception\Common\UploadFileException;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CsvStorageService
{
    /**
     * @throws UploadFileException
     * @throws Exception
     */
    public function storeFile(UploadedFile $uploadedFile, string $path): string
    {
        $this->validateFileExtension($uploadedFile);
        $fileName = $this->generateFileName($uploadedFile->getClientOriginalName(), 'csv');

        try {
            $uploadedFile->move($path, $fileName);
        } catch (FileException $e) {
        }

        return $path . '/' . $fileName;
    }

    /**
     * @throws UploadFileException
     */
    private function validateFileExtension(UploadedFile $uploadedFile): void
    {
        $mimeType = explode('/', $uploadedFile->getMimeType());
        if (false === in_array($mimeType[0], ['text', 'plain', 'application', 'csv', 'CSV'])) {
            throw new UploadFileException(UploadFileException::INVALID_FILE_EXTENSION, ['reason' => 'file extension is invalid']);
        }
    }

    /**
     * @throws Exception
     */
    private function generateFileName(string $originalFileName, string $extension): string
    {
        return 'import_' . (new DateTime())->getTimestamp() . '_' . md5(random_int(0, 1000000) . $originalFileName) . '.' . $extension;
    }
}
