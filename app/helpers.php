<?php
if ( ! function_exists('config_path'))
{
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}

if ( ! function_exists('in_array_custom'))
{
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function in_array_custom($sNeedle , $aHaystack)
    {
        foreach ($aHaystack as $sKey)
        {
            if( stripos( strtolower($sKey) , strtolower($sNeedle) ) !== false )
            {
                return true;
            }
        }

        return false;
    }
}