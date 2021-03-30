<?php

namespace Botble\Base\Supports;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Arr;
use ZipArchive;

class Core
{

    /**
     * @var string
     */
    protected $productId;

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $apiLanguage;

    /**
     * @var string
     */
    protected $currentVersion;

    /**
     * @var string
     */
    protected $verifyType;

    /**
     * @var int
     */
    protected $verificationPeriod;

    /**
     * @var false|string
     */
    protected $currentPath;

    /**
     * @var string
     */
    protected $rootPath;

    /**
     * @var string
     */
    protected $licenseFile;

    /**
     * @var bool
     */
    protected $showUpdateProcess = true;

    /**
     * @var string
     */
    protected $sessionKey = '44622179e10cab6';

    /**
     * Core constructor.
     * @throws FileNotFoundException
     */
    public function __construct()
    {
        $this->apiUrl = 'https://license.botble.com/';
        $this->apiKey = 'CAF4B17F6D3F656125F9';
        $this->apiLanguage = 'english';
        $this->currentVersion = get_cms_version();
        $this->verificationPeriod = 1;
        $this->currentPath = realpath(__DIR__);
        $this->rootPath = base_path();
        $this->licenseFile = storage_path('.license');

        $core = get_file_data(core_path('core.json'));

        if ($core) {
            $this->productId = Arr::get($core, 'productId');
            $this->verifyType = Arr::get($core, 'source');
        }
    }

    /**
     * @return string
     */
    public function getCurrentVersion()
    {
        return $this->currentVersion;
    }

    /**
     * @return string
     */
    public function getLicenseFilePath()
    {
        return $this->licenseFile;
    }

    /**
     * @return mixed
     */
    public function checkConnection()
    {
        $dataArray = [];
        $getData = $this->callApi(
            'POST',
            $this->apiUrl . 'api/check_connection_ext',
            json_encode($dataArray)
        );
        $response = json_decode($getData, true);

        return $response;
    }

    /**
     * @param string $method
     * @param string $url
     * @param string $data
     * @return bool|false|string
     */
    protected function callApi(string $method, string $url, ?string $data)
    {
        $curl = curl_init();
        switch ($method) {
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case 'PUT':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            default:
                if ($data) {
                    $url = sprintf('%s?%s', $url, http_build_query($data));
                }
        }

        $thisServerSame = request()->server('SERVER_NAME') ?: request()->server('HTTP_HOST');

        $thisHttpOrHttps = request()->server('HTTPS') == 'on' || request()->server('HTTP_X_FORWARDED_PROTO') == 'https'
            ? 'https://' : 'http://';

        $thisUrl = $thisHttpOrHttps . $thisServerSame . request()->server('REQUEST_URI');
        $thisIp = request()->server('SERVER_ADDR') ?: $this->getIpFromThirdParty() ?: gethostbyname(gethostname());

        curl_setopt($curl, CURLOPT_HTTPHEADER,
            [
                'Content-Type: application/json',
                'LB-API-KEY: ' . $this->apiKey,
                'LB-URL: ' . $thisUrl,
                'LB-IP: ' . $thisIp,
                'LB-LANG: ' . $this->apiLanguage,
            ]
        );
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        $result = curl_exec($curl);
        if (!$result && config('app.debug')) {
            $rs = [
                'status'  => false,
                'message' => 'Server is unavailable at the moment, please try again.',
            ];
            return json_encode($rs);
        }
        $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($httpStatus != 200) {
            if (config('app.debug')) {
                $tempDecode = json_decode($result, true);
                $rs = [
                    'status'  => false,
                    'message' => !empty($tempDecode['error']) ? $tempDecode['error'] : $tempDecode['message'],
                ];
                return json_encode($rs);
            }
            $rs = [
                'status'  => false,
                'message' => 'Server returned an invalid response, please contact support.',
            ];
            return json_encode($rs);
        }
        curl_close($curl);

        return $result;
    }

    /**
     * @return bool|string
     */
    protected function getIpFromThirdParty()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://ipecho.net/plain');
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    /**
     * @return mixed
     */
    public function getLatestVersion()
    {
        $dataArray = [
            'product_id' => $this->productId,
        ];
        $getData = $this->callApi(
            'POST',
            $this->apiUrl . 'api/latest_version',
            json_encode($dataArray)
        );
        $response = json_decode($getData, true);

        return $response;
    }

    /**
     * @param $license
     * @param $client
     * @param bool $createLicense
     * @return mixed
     */
    public function activateLicense($license, $client, $createLicense = true)
    {
        $dataArray = [
            'product_id'   => $this->productId,
            'license_code' => $license,
            'client_name'  => $client,
            'verify_type'  => $this->verifyType,
        ];

        $getData = $this->callApi(
            'POST',
            $this->apiUrl . 'api/activate_license',
            json_encode($dataArray)
        );
        $response = json_decode($getData, true);

        if (!empty($createLicense)) {
            if ($response['status']) {
                $license = trim($response['lic_response']);
                file_put_contents($this->licenseFile, $license, LOCK_EX);
            } else {
                @chmod($this->licenseFile, 0777);
                if (is_writeable($this->licenseFile)) {
                    unlink($this->licenseFile);
                }
            }
        }
        return $response;
    }

    /**
     * @param bool $timeBasedCheck
     * @param bool $license
     * @param bool $client
     * @return array|mixed
     */
    public function verifyLicense($timeBasedCheck = false, $license = false, $client = false)
    {
        if (!empty($license) && !empty($client)) {
            $dataArray = [
                'product_id'   => $this->productId,
                'license_file' => null,
                'license_code' => $license,
                'client_name'  => $client,
            ];
        } elseif ($this->checkLocalLicenseExist()) {
            $dataArray = [
                'product_id'   => $this->productId,
                'license_file' => file_get_contents($this->licenseFile),
                'license_code' => null,
                'client_name'  => null,
            ];
        } else {
            $dataArray = [
                'product_id'   => $this->productId,
                'license_file' => null,
                'license_code' => null,
                'client_name'  => null,
            ];
        }
        $res = ['status' => true, 'message' => 'Verified! Thanks for purchasing.'];
        if ($timeBasedCheck && $this->verificationPeriod > 0) {
            $type = (int)$this->verificationPeriod;
            $today = date('d-m-Y');
            if (!session($this->sessionKey)) {
                session([$this->sessionKey => '00-00-0000']);
            }
            if ($type == 1) {
                $typeText = '1 day';
            } elseif ($type == 3) {
                $typeText = '3 days';
            } elseif ($type == 7) {
                $typeText = '1 week';
            } else {
                $typeText = $type . ' days';
            }
            if (strtotime($today) >= strtotime(session($this->sessionKey))) {
                $getData = $this->callApi(
                    'POST',
                    $this->apiUrl . 'api/verify_license',
                    json_encode($dataArray)
                );
                $res = json_decode($getData, true);
                if ($res['status'] == true) {
                    $tomo = date('d-m-Y', strtotime($today . ' + ' . $typeText));
                    session([$this->sessionKey => $tomo]);
                }
            }
        } else {
            $getData = $this->callApi(
                'POST',
                $this->apiUrl . 'api/verify_license',
                json_encode($dataArray)
            );
            $res = json_decode($getData, true);
        }
        return $res;
    }

    /**
     * @return bool
     */
    public function checkLocalLicenseExist()
    {
        return is_file($this->licenseFile);
    }

    /**
     * @param bool $license
     * @param bool $client
     * @return mixed
     */
    public function deactivateLicense($license = false, $client = false)
    {
        if (!empty($license) && !empty($client)) {
            $dataArray = [
                'product_id'   => $this->productId,
                'license_file' => null,
                'license_code' => $license,
                'client_name'  => $client,
            ];
        } else {
            if (is_file($this->licenseFile)) {
                $dataArray = [
                    'product_id'   => $this->productId,
                    'license_file' => file_get_contents($this->licenseFile),
                    'license_code' => null,
                    'client_name'  => null,
                ];
            } else {
                $dataArray = [];
            }
        }
        $getData = $this->callApi(
            'POST',
            $this->apiUrl . 'api/deactivate_license',
            json_encode($dataArray)
        );
        $response = json_decode($getData, true);
        if ($response['status']) {
            session()->forget($this->sessionKey);
            @chmod($this->licenseFile, 0777);
            if (is_writeable($this->licenseFile)) {
                unlink($this->licenseFile);
            }
        }

        return $response;
    }

    /**
     * @return mixed
     */
    public function checkUpdate()
    {
        $dataArray = [
            'product_id'      => $this->productId,
            'current_version' => $this->currentVersion,
        ];
        $getData = $this->callApi(
            'POST',
            $this->apiUrl . 'api/check_update',
            json_encode($dataArray)
        );
        $response = json_decode($getData, true);

        return $response;
    }

    /**
     * @param $updateId
     * @param $type
     * @param $version
     * @param bool $license
     * @param bool $client
     */
    public function downloadUpdate($updateId, $type, $version, $license = false, $client = false)
    {
        if (!empty($license) && !empty($client)) {
            $dataArray = [
                'license_file' => null,
                'license_code' => $license,
                'client_name'  => $client,
            ];
        } elseif (is_file($this->licenseFile)) {
            $dataArray = [
                'license_file' => file_get_contents($this->licenseFile),
                'license_code' => null,
                'client_name'  => null,
            ];
        } else {
            $dataArray = [];
        }
        ob_end_flush();
        ob_implicit_flush(true);
        $version = str_replace('.', '_', $version);
        ob_start();
        $sourceSize = $this->apiUrl . 'api/get_update_size/main/' . $updateId;
        echo 'Preparing to download main update...' . '<br>';
        if ($this->showUpdateProcess) {
            echo '<script>document.getElementById(\'prog\').value = 1;</script>';
        }
        ob_flush();
        echo 'Main Update size: ' . $this->getRemoteFileSize($sourceSize) . ' (Please do not refresh the page).<br>';
        if ($this->showUpdateProcess) {
            echo '<script>document.getElementById(\'prog\').value = 5;</script>';
        }
        ob_flush();
        $ch = curl_init();
        $source = $this->apiUrl . 'api/download_update/main/' . $updateId;
        curl_setopt($ch, CURLOPT_URL, $source);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataArray);

        $thisServerSame = request()->server('SERVER_NAME') ?: request()->server('HTTP_HOST');

        $thisHttpOrHttps = request()->server('HTTPS') == 'on' || request()->server('HTTP_X_FORWARDED_PROTO') == 'https'
            ? 'https://' : 'http://';

        $thisUrl = $thisHttpOrHttps . $thisServerSame . request()->server('REQUEST_URI');
        $thisIp = request()->server('SERVER_ADDR') ?: $this->getIpFromThirdParty() ?: gethostbyname(gethostname());

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'LB-API-KEY: ' . $this->apiKey,
                'LB-URL: ' . $thisUrl,
                'LB-IP: ' . $thisIp,
                'LB-LANG: ' . $this->apiLanguage,
            ]
        );
        if ($this->showUpdateProcess) {
            curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, [$this, 'progress']);
        }
        if ($this->showUpdateProcess) {
            curl_setopt($ch, CURLOPT_NOPROGRESS, false);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        echo 'Downloading main update...<br>';
        if ($this->showUpdateProcess) {
            echo '<script>document.getElementById(\'prog\').value = 10;</script>';
        }
        ob_flush();
        $data = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpStatus != 200) {
            if ($httpStatus == 401) {
                curl_close($ch);
                exit('<br>Your update period has ended or your license is invalid, please contact support.');
            } else {
                curl_close($ch);
                exit('<br>' . 'Server returned an invalid response, please contact support.');
            }
        }
        curl_close($ch);
        $destination = $this->rootPath . '/update_main_' . $version . '.zip';
        $file = fopen($destination, 'w+');
        if (!$file) {
            exit('<br>Folder does not have write permission or the update file path could not be resolved, please contact support.');
        }
        fputs($file, $data);
        fclose($file);
        if ($this->showUpdateProcess) {
            echo '<script>document.getElementById(\'prog\').value = 65;</script>';
        }
        ob_flush();
        $zip = new ZipArchive;
        $res = $zip->open($destination);
        if ($res === true) {
            $zip->extractTo($this->rootPath . '/');
            $zip->close();
            unlink($destination);
            echo 'Main update files downloaded and extracted.<br><br>';
            if ($this->showUpdateProcess) {
                echo '<script>document.getElementById(\'prog\').value = 75;</script>';
            }
            ob_flush();
        } else {
            echo 'Update zip extraction failed.<br><br>';
            ob_flush();
        }
        if ($type == true) {
            $sourceSize = $this->apiUrl . 'api/get_update_size/sql/' . $updateId;
            echo 'Preparing to download SQL update...<br>';
            ob_flush();
            echo 'SQL Update size: ' . $this->getRemoteFileSize($sourceSize) . ' (Please do not refresh the page).<br>';
            if ($this->showUpdateProcess) {
                echo '<script>document.getElementById(\'prog\').value = 85;</script>';
            }
            ob_flush();
            $ch = curl_init();
            $source = $this->apiUrl . 'api/download_update/sql/' . $updateId;
            curl_setopt($ch, CURLOPT_URL, $source);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataArray);

            $thisServerSame = request()->server('SERVER_NAME') ?: request()->server('HTTP_HOST');

            $thisHttpOrHttps = request()->server('HTTPS') == 'on' || request()->server('HTTP_X_FORWARDED_PROTO') == 'https'
                ? 'https://' : 'http://';

            $thisUrl = $thisHttpOrHttps . $thisServerSame . request()->server('REQUEST_URI');
            $thisIp = request()->server('SERVER_ADDR') ?: $this->getIpFromThirdParty() ?: gethostbyname(gethostname());

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'LB-API-KEY: ' . $this->apiKey,
                    'LB-URL: ' . $thisUrl,
                    'LB-IP: ' . $thisIp,
                    'LB-LANG: ' . $this->apiLanguage,
                ]
            );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            echo 'Downloading SQL update...<br>';
            if ($this->showUpdateProcess) {
                echo '<script>document.getElementById(\'prog\').value = 90;</script>';
            }
            ob_flush();
            $data = curl_exec($ch);
            $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpStatus != 200) {
                curl_close($ch);
                exit('Server returned an invalid response, please contact support.');
            }
            curl_close($ch);
            $destination = $this->rootPath . '/update_sql_' . $version . '.sql';
            $file = fopen($destination, 'w+');
            if (!$file) {
                exit('Folder does not have write permission or the update file path could not be resolved, please contact support.');
            }
            fputs($file, $data);
            fclose($file);
            echo 'SQL update files downloaded.<br><br>';
            if ($this->showUpdateProcess) {
                echo '<script>document.getElementById(\'prog\').value = 100;</script>';
            }
            echo 'Update successful, please import the downloaded sql file in your database.';
            ob_flush();
        } else {
            if ($this->showUpdateProcess) {
                echo '<script>document.getElementById(\'prog\').value = 100;</script>';
            }
            echo 'Update successful, there were no SQL updates. So you can run the updated application directly.';
            ob_flush();
        }
        ob_end_flush();
    }

    /**
     * @param string $url
     * @return string
     */
    protected function getRemoteFileSize($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_NOBODY, true);

        $thisServerSame = request()->server('SERVER_NAME') ?: request()->server('HTTP_HOST');

        $thisHttpOrHttps = request()->server('HTTPS') == 'on' || request()->server('HTTP_X_FORWARDED_PROTO') == 'https'
            ? 'https://' : 'http://';

        $thisUrl = $thisHttpOrHttps . $thisServerSame . request()->server('REQUEST_URI');
        $thisIp = request()->server('SERVER_ADDR') ?:
            $this->getIpFromThirdParty() ?:
                gethostbyname(gethostname());

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'LB-API-KEY: ' . $this->apiKey,
                'LB-URL: ' . $thisUrl,
                'LB-IP: ' . $thisIp,
                'LB-LANG: ' . $this->apiLanguage,
            ]
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_exec($curl);
        $filesize = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        if ($filesize) {
            switch ($filesize) {
                case $filesize < 1024:
                    $size = $filesize . ' B';
                    break;
                case $filesize < 1048576:
                    $size = round($filesize / 1024, 2) . ' KB';
                    break;
                case $filesize < 1073741824:
                    $size = round($filesize / 1048576, 2) . ' MB';
                    break;
                case $filesize < 1099511627776:
                    $size = round($filesize / 1073741824, 2) . ' GB';
                    break;
            }
            return $size;
        }
    }

    /**
     * @param $resource
     * @param $downloadSize
     * @param $downloaded
     * @param $uploadSize
     * @param $uploaded
     */
    protected function progress($resource, $downloadSize, $downloaded, $uploadSize, $uploaded)
    {
        static $prev = 0;
        if ($downloadSize == 0) {
            $progress = 0;
        } else {
            $progress = round($downloaded * 100 / $downloadSize);
        }
        if (($progress != $prev) && ($progress == 25)) {
            $prev = $progress;
            echo '<script>document.getElementById(\'prog\').value = 22.5;</script>';
            ob_flush();
        }
        if (($progress != $prev) && ($progress == 50)) {
            $prev = $progress;
            echo '<script>document.getElementById(\'prog\').value = 35;</script>';
            ob_flush();
        }
        if (($progress != $prev) && ($progress == 75)) {
            $prev = $progress;
            echo '<script>document.getElementById(\'prog\').value = 47.5;</script>';
            ob_flush();
        }
        if (($progress != $prev) && ($progress == 100)) {
            $prev = $progress;
            echo '<script>document.getElementById(\'prog\').value = 60;</script>';
            ob_flush();
        }
    }
}
