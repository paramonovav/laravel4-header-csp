<?php namespace Paramonovav\Laravel4HeaderCsp;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class Laravel4HeaderCspServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('paramonovav/laravel4-header-csp');

        $this->app['router']-> post(
            Config::get('laravel4-header-csp::report-uri-route-uri', 'content-security-policy-report'),
            array(
            'as' => Config::get('laravel4-header-csp::report-uri-route-name', 'header-csp-report'),
            function(){

            	$post_data = file_get_contents('php://input');

            	if (empty($post_data))
            	{
            		return;
            	}

            	$report_folder_path = storage_path().'/logs/content-security-policy-report';

            	if (!is_dir($report_folder_path))
            	{
            		mkdir($report_folder_path, 0777, TRUE);
            	}

            	file_put_contents($report_folder_path.'/'.date('Y-m-d').'.log', $post_data."\r\n", FILE_APPEND | LOCK_EX);
            }
        ));

        $this->app['router']-> filter(
            'response.secure',
            function($route, $request, $response, $value = ''){

            	$profiles = Config::get('laravel4-header-csp::default', 'global');

            	if (!empty($value))
            	{
            		$profiles .= '-'.$value;
            	}
            	$profiles = explode('-', $profiles);
            	$profiles = array_map('trim', $profiles);

            	$config_profiles = Config::get('laravel4-header-csp::profiles');

            	if (empty($config_profiles) || empty($profiles))
            	{
            		return;
            	}

            	$csp = array();
            	foreach ($profiles as $profile)
            	{
            		if (empty($config_profiles[$profile]))
            		{
            			continue;
            		}

            		$config_profile = $config_profiles[$profile];

            		if (!empty($config_profile['csp']) && is_array($config_profile['csp']))
            		{
            			foreach ($config_profile['csp'] as $key => $value)
            			{
            				if (!is_array($value))
            				{
            					if ('report-uri' == $key)
            					{
            						$value = route($value);
            					}
            					$value = array($value);
            				}

            				foreach ($value as $val)
            				{
            					$csp[$key][$val] = $val;
            				}
            			}
            		}

            		if (!empty($config_profile['custom']) && is_array($config_profile['custom']))
            		{
	            		foreach ($config_profile['custom'] as $key => $value)
	            		{
							$response-> headers->set($key, $value);
	            		}
            		}
            	}

            	foreach ($csp as $key => $vals)
            	{
            		if (isset($vals["'none'"]) && count($vals) > 1)
            		{
            			unset($vals["'none'"]);
            		}

            		$csp_parts[$key] = $key.' '.implode(' ', $vals);
            	}

            	$csp_line = implode('; ', $csp_parts).';';

            	if (FALSE === Config::get('laravel4-header-csp::csp-report-only', FALSE))
            	{
					$response-> headers->set('X-Webkit-CSP', $csp_line);
					$response-> headers->set('X-Content-Security-Policy', $csp_line);
					$response-> headers->set('Content-Security-Policy', $csp_line);
				}
				else
				{
					$response-> headers->set('X-Content-Security-Policy-Report-Only', $csp_line);
					$response-> headers->set('Content-Security-Policy-Report-Only', $csp_line);
				}
            }
        );
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
