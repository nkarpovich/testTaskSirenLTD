<?php

namespace Siren\CommissionTask\Input;

use InputException;

class CsvInput implements inputInterface
{
    private string $filePath;

    /**
     * @param string $filePath
     */
    public function __construct(string $filePath) {
        $this->setFilePath($filePath);
    }

    /**
     * @return array
     */
    public function getInputData(): array {
        $resData = [];
        if (($handle = fopen($this->getFilePath(), "r")) !== false) {
            while (($data = fgetcsv($handle, 10000, ",")) !== false) {
                $resData[] = $data;
            }
            fclose($handle);
        }
        else {
            throw new InputException('Can`t open file');
        }
        return $resData;
    }

    /**
     * @return string
     */
    public function getFilePath(): string {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath(string $filePath): void {
        $this->filePath = $filePath;
    }
}
