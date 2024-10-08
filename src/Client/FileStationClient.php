<?php

namespace  Kazio73\SynologyApiClient\Client;

/**
 * Class Client.
 */
class FileStationClient extends Client
{

    public const API_SERVICE_NAME = 'FileStation';

    public const API_NAMESPACE = 'SYNO';

    /**
     * Info API setup
     *
     * @param string $address
     * @param int $port
     * @param string $protocol
     * @param int $version
     * @param boolean $verifySSL
     */
    public function __construct(
        $address,
        $port = null,
        $protocol = null,
        $version = 1,
        $verifySSL = false
    )
    {
        parent::__construct(
            self::API_SERVICE_NAME,
            self::API_NAMESPACE,
            $address,
            $port,
            $protocol,
            $version,
            $verifySSL
        );
    }

    /**
     * Return Information about VideoStation
     * - is_manager
     * - version
     * - version_string
     * @throws SynologyException
     */
    public function getInfo(): array
    {
        return $this->request(self::API_SERVICE_NAME, 'Info', 'FileStation/info.cgi', 'getinfo');
    }

    /**
     * Get Available Shares
     *
     * @param bool $onlywritable
     * @param int $limit
     * @param int $offset
     * @param string $sortby
     * @param string $sortdirection
     * @param bool $additional
     * @return array
     * @throws SynologyException
     */
    public function getShares(
        $onlywritable = false,
        $limit = 25,
        $offset = 0,
        $sortby = 'name',
        $sortdirection = 'asc',
        $additional = false
    ): array
    {
        return $this->request(
            self::API_SERVICE_NAME,
            'List',
            'entry.cgi',
            'list_share',
            [
                'onlywritable' => $onlywritable,
                'limit' => $limit,
                'offset' => $offset,
                'sort_by' => $sortby,
                'sort_direction' => $sortdirection,
                'additional' => $additional ? 'real_path,owner,time,perm,volume_status' : '',
            ]
        );
    }

    /**
     * Get info about an object
     *
     * @param string $type (List|Sharing)
     * @param string $id
     * @return array
     * @throws SynologyException
     */
    public function getObjectInfo($type, $id): array
    {
        switch ($type) {
            case 'List':
                $path = 'entry.cgi';
                break;
            case 'Sharing':
                $path = 'FileStation/file_sharing.cgi';
                break;
            default:
                throw new SynologyException('Unknow "' . $type . '" object');
        }

        return $this->request(self::API_SERVICE_NAME, $type, $path, 'getinfo', ['id' => $id]);
    }

    /**
     * Get a list of files/directories in a given path
     *
     * @param string $path like '/home'
     * @param int $limit
     * @param int $offset
     * @param string $sortby (name|size|user|group|mtime|atime|ctime|crtime|posix|type)
     * @param string $sortdirection
     * @param string $pattern
     * @param string $filetype (all|file|dir)
     * @param bool $additional
     * @return mixed
     * @throws SynologyException
     */
    public function getList(
        $path = '/home',
        $limit = 25,
        $offset = 0,
        $sortby = 'name',
        $sortdirection = 'asc',
        $pattern = '',
        $filetype = 'all',
        $additional = false
    )
    {
        $path = $this->escapeParam($path);

        return $this->request(
            self::API_SERVICE_NAME,
            'List',
            'entry.cgi',
            'list',
            [
                'folder_path' => $path,
                'limit' => $limit,
                'offset' => $offset,
                'sort_by' => $sortby,
                'sort_direction' => $sortdirection,
                'pattern' => $pattern,
                'filetype' => $filetype,
                'additional' => $additional ? 'real_path,size,owner,time,perm' : '',
            ]
        );
    }

    /**
     * Get information of files/directories in a given paths
     *
     * @param array $path
     * @param bool $additional
     * @return mixed
     * @throws SynologyException
     */
    public function getPathInfo(
        $path = array(),
        $additional = false
    )
    {
        $path = $this->escapeParam($path);

        return $this->request(
            self::API_SERVICE_NAME,
            'List',
            'entry.cgi',
            'getinfo',
            [
                'path' => implode(',', $path),
                'additional' => $additional ? 'real_path,size,owner,time,perm' : '',
            ]
        );
    }

    /**
     * Upload file to given path
     *
     * @param $file
     * @param $filename
     * @return mixed
     * @throws SynologyException
     */
    public function uploadFile($file, $filename, $path = '/home')
    {
        return $this->request(
            self::API_SERVICE_NAME,
            'Upload',
            'entry.cgi',
            'upload',
            [
                'path' => $path,
                'overwrite' => 'false',
                'create_parents' => 'true',
                'filename' => $filename,
            ],
            2,
            'post',
            $file
        );
    }

    /**
     * Search for files/directories in a given path
     *
     * @param string $pattern
     * @param string $path like '/home'
     * @param int $limit
     * @param int $offset
     * @param string $sortby (name|size|user|group|mtime|atime|ctime|crtime|posix|type)
     * @param string $sortdirection (asc|desc)
     * @param string $filetype (all|file|dir)
     * @param bool $additional
     * @return array
     * @throws SynologyException
     */
    public function search(
        $pattern,
        $path = '/home',
        $limit = 25,
        $offset = 0,
        $sortby = 'name',
        $sortdirection = 'asc',
        $filetype = 'all',
        $additional = false
    ): array
    {
        return $this->request(
            self::API_SERVICE_NAME,
            'List',
            'entry.cgi',
            'list',
            [
                'folder_path' => $path,
                'limit' => $limit,
                'offset' => $offset,
                'sort_by' => $sortby,
                'sort_direction' => $sortdirection,
                'pattern' => $pattern,
                'filetype' => $filetype,
                'additional' => $additional ? 'real_path,size,owner,time,perm' : '',
            ]
        );
    }

    /**
     * Download a file
     *
     * @param string $path (comma separated)
     * @param string $mode
     * @return array
     * @throws SynologyException
     */
    public function download($path, $mode = 'open'): array
    {
        return $this->request(
            self::API_SERVICE_NAME,
            'Download',
            'entry.cgi',
            'download',
            [
                'path' => $path,
                'mode' => $mode
            ]
        );
    }

    /**
     * Download a file
     *
     * @param string $path (comma separated)
     * @param string $name
     * @return array
     * @throws SynologyException
     */
    public function rename(string $path, string $name): array
    {
        return $this->request(
            self::API_SERVICE_NAME,
            'List',
            'entry.cgi',
            'rename',
            [
                'path' => $path,
                'name' => $name,
            ]
        );
    }

    /**
     * ListShare a file
     *
     * @return array
     * @throws SynologyException
     */
    public function shareList(): array
    {
        return $this->request(
            self::API_SERVICE_NAME,
            'Sharing',
            'entry.cgi',
            'list',
            [
                'offset' => 0,
                'limit' => 10,
            ],
            3
        );
    }

    /**
     * CreateShare a file
     *
     * @param string $path (comma separated)
     * @param string $name
     * @return array
     * @throws SynologyException
     */
    public function shareCreate(string $path): array
    {
        return $this->request(
            self::API_SERVICE_NAME,
            'Sharing',
            'entry.cgi',
            'create',
            [
                'path' => $path,
                'password' => 'KazioK',
                'date_expired' => '' ,
                'date_available' => '' ,
            ],
            3
        );
    }


    /**
     * Delete file from a given path
     *
     * @param string $path like '/home'
     * @return mixed
     * @throws SynologyException
     */
    public function delete($path)
    {
        return $this->request(
            self::API_SERVICE_NAME,
            'Delete',
            'entry.cgi',
            'delete',
            ['path' => $path],
            1
        );
    }

    /**
     * Create a folder inside a given path
     *
     * @param string $path like '/home'
     * @param string $name
     * @param bool $force_parent
     * @param bool $additional
     * @return mixed
     * @throws SynologyException
     */
    public function createFolder(
        $path = '/home',
        $name = '',
        $force_parent = false,
        $additional = false
    )
    {
        $path = $this->escapeParam($path);
        $name = $this->escapeParam($name);

        return $this->request(
            self::API_SERVICE_NAME,
            'CreateFolder',
            'entry.cgi',
            'create',
            [
                'folder_path' => $path,
                'name' => $name,
                'force_parent' => $force_parent,
                'additional' => $additional ? 'real_path,size,owner,time,perm,type' : '',
            ]
        );
    }
    }
