<?php
namespace Siren\CommissionTask\Input;


class CsvInput implements inputInterface
{
    private string $filePath;

    public function __construct(string $filePath){
       $this->setFilePath($filePath);
    }
    public function getInputData(): array {
        $resData = [];
        if (($handle = fopen($this->getFilePath(), "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                $resData[] = $data;
            }
            fclose($handle);
        }else{
            throw new \InputException('Can`t open file');
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