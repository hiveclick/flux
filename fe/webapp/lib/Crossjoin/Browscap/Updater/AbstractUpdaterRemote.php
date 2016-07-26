<?php
namespace Crossjoin\Browscap\Updater;

/**
 * Abstract updater class (for remote sources)
 *
 * With class extends the abstract updater with methods that are required
 * or remote sources.
 *
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 Christoph Ziegenberg <christoph@ziegenberg.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @package Crossjoin\Browscap
 * @author Christoph Ziegenberg <christoph@ziegenberg.com>
 * @copyright Copyright (c) 2014 Christoph Ziegenberg <christoph@ziegenberg.com>
 * @version 0.1
 * @license http://www.opensource.org/licenses/MIT MIT License
 * @link https://github.com/crossjoin/browscap
 */
abstract class AbstractUpdaterRemote
extends AbstractUpdater
{
    /**
     * The URL to get the current Browscap data (in the configured format)
     *
     * @var string
     */
    protected $browscapSourceUrl        = 'http://browscap.org/stream?q=%t';

    /**
     * The URL to detect the current Browscap version
     * (time string like 'Thu, 08 May 2014 07:17:44 +0000' that is converted to a time stamp)
     *
     * @var string
     */
    protected $browscapVersionUrl       = 'http://browscap.org/version';

    /**
     * The URL to detect the current Browscap version number
     *
     * @var string
     */
    protected $browscapVersionNumberUrl = 'http://browscap.org/version-number';

    /**
     * The user agent to include in the requests made by the class during the
     * update process. (Based on the user agent in the official Browscap-PHP class)
     *
     * @var string
     */
    protected $userAgent = 'Browser Capabilities Project - Crossjoin Browscap/%v %m';

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    /**
     * Gets the current browscap version (time stamp)
     *
     * @return int
     */
    public function getBrowscapVersion()
    {
        return (int)strtotime($this->getRemoteData($this->getBrowscapVersionUrl()));
    }

    /**
     * Gets the URL for requesting the current browscap version (time string)
     *
     * @return string
     */
    protected function getBrowscapVersionUrl()
    {
        return $this->browscapVersionUrl;
    }

    /**
     * Gets the current browscap version number
     *
     * @return int
     */
    public function getBrowscapVersionNumber()
    {
        return (int)$this->getRemoteData($this->getBrowscapVersionNumberUrl());
    }

    /**
     * Gets the URL for requesting the current browscap version number
     *
     * @return string
     */
    protected function getBrowscapVersionNumberUrl()
    {
        return $this->browscapVersionNumberUrl;
    }

    /**
     * Gets the browscap data of the used source type
     *
     * @return string
     */
    public function getBrowscapSource()
    {
        $type = \Crossjoin\Browscap\Browscap::getParser()->getSourceType();
        $url  = str_replace('%t', urlencode($type), $this->getBrowscapSourceUrl());

        return $this->getRemoteData($url);
    }

    /**
     * Gets the URL for requesting the browscap data
     *
     * @return string
     */
    protected function getBrowscapSourceUrl()
    {
        return $this->browscapSourceUrl;
    }

    /**
     * Format the useragent string to be used in the remote requests made by the
     * class during the update process
     *
     * @return string
     */
    protected function getUserAgent()
    {
        return str_replace(
            array('%v', '%m'),
            array(\Crossjoin\Browscap\Browscap::VERSION, $this->getUpdateMethod()),
            $this->userAgent
        );
    }

    /**
     * Gets the data from a given URL (or false on failure)
     *
     * @param string $url
     * @return string|boolean
     */
    abstract protected function getRemoteData($url);
}