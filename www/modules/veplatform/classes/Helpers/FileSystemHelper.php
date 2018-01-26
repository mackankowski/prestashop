<?php
/**
 * NOTICE OF LICENSE
 *
 * @author    Ve Interactive <info@veinteractive.com>
 * @copyright 2016 Ve Interactive
 * @license   MIT License
 *
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

class FileSystemHelper implements FileSystemHelperInterface
{
    /**
     * @var RecursiveIteratorIterator $iterator
     */
    private $iterator;

    /**
     * @var VeLogger $logger
     */
    private $logger;


    public function __construct(RecursiveIteratorIterator $iterator, VeLogger $logger)
    {
        $this->logger = $logger;
        $this->iterator = $iterator;
    }

    /**
     * Compare the folder and files structures and remove the unnecessary ones
     *
     * @param string $modulePath
     *
     * @return void
     */
    public function cleanRemainingFiles($modulePath)
    {
        try {
            if (!isset($modulePath) || empty($modulePath)) {
                return;
            }

            $modulePath = $this->makePathCompatible($modulePath);
            //remove last slash
            $modulePath = Tools::substr($modulePath, 0, -1);
            $fileStructureJson = $modulePath . '/upgrade/fileStructure.json';

            $folderStructure = array();
            if (file_exists($fileStructureJson)) {
                $folderStructure = Tools::file_get_contents($fileStructureJson);
                $folderStructure = json_decode($folderStructure, true);
            }

            $folderStructure = $this->transformSlashes($folderStructure, $modulePath);

            $currentFolderStructure = $this->getFolderStructure($modulePath);
            $currentFolderStructure = $this->transformSlashes($currentFolderStructure);

            $remainingFiles = array_diff($currentFolderStructure, $folderStructure);

            $this->logger->logMessage('Start - Removing these ' . count($remainingFiles) . ' folders/files: ' . print_r($remainingFiles, true));
            $remainingFiles = $this->removeFiles($remainingFiles);
            $remainingFiles = $this->removeFolders($remainingFiles);

            if (empty($remainingFiles)) {
                $this->logger->logMessage('End - All files removed successfully');
                return;
            }

            $this->logger->logMessage('End - Not all files/folders removed successfully. Remaining files: ' . print_r($remainingFiles, true));
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }
    }

    /**
     * Transform slashes to be compatible both on Windows and Linux and add full path if it's given
     *
     * @param array  $folder
     *
     * @param string $modulePath
     *
     * @return array
     */
    private function transformSlashes($folder, $modulePath = '')
    {
        if (!isset($folder)) {
            return array();
        }

        //we add the full path to the files we want to compare and replace slashes
        foreach ($folder as $key => $filename) {
            if (strpos($filename, '\\') !== false) {
                $filename = str_replace('\\', '/', $filename);
            }
            $folder[$key] = $modulePath . $filename;
        }

        return $folder;
    }

    /**
     * Remove the files given
     *
     * @param array $remainingFiles
     *
     * @return array
     */
    private function removeFiles($remainingFiles)
    {
        foreach ($remainingFiles as $key => $file) {
            if (file_exists($file) && !is_dir($file)) {
                unlink($file);
                unset($remainingFiles[$key]);
            }
        }

        return $remainingFiles;
    }

    /**
     * Remove the folders given
     *
     * @param array $remainingFolders
     *
     * @return array
     */
    private function removeFolders($remainingFolders)
    {
        foreach ($remainingFolders as $key => $folder) {
            if (file_exists($folder) && is_dir($folder)) {
                //remove nested folders
                $this->removeFoldersRecursively($folder);
                unset($remainingFolders[$key]);
            }

            //if folder was removed already
            unset($remainingFolders[$key]);
        }

        return $remainingFolders;
    }

    /**
     * If given folder contains other folders, remove them
     *
     * @param string $folder
     *
     * @return void
     */
    private function removeFoldersRecursively($folder)
    {
        $objects = scandir($folder);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                //check current folder contains nested folders and remove them
                if (is_dir($folder . DIRECTORY_SEPARATOR . $object)) {
                    $this->removeFoldersRecursively($folder . DIRECTORY_SEPARATOR . $object);
                }
            }
        }
        rmdir($folder);
    }

    /**
     * Returns an array with all the folders and files names from the provided folder path
     *
     * @param string $folder
     *
     * @return array
     */
    public function getFolderStructure($folder)
    {
        $paths = array($folder);
        foreach ($this->iterator as $name => $object) {
            $paths[] = $name;
        }

        return $paths;
    }

    /**
     * Make path to be compatible both on Windows and Linux
     *
     * @param string $modulePath
     *
     * @return string
     */
    private function makePathCompatible($modulePath)
    {
        if (strpos($modulePath, '\\') !== false) {
            $modulePath = str_replace('\\', '/', $modulePath);
        }
        return $modulePath;
    }
}
