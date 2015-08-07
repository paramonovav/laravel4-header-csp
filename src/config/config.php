<?php
/*
 * 
 * http://content-security-policy.com/
 * http://www.sitepoint.com/improving-web-security-with-the-content-security-policy/
 * https://www.owasp.org/index.php/List_of_useful_HTTP_headers
 *
 */

return array(

	'report-uri-route-uri' => 'content-security-policy-report',
	'report-uri-route-name' => 'header-csp-report',

	'default' => 'global',
    
    'csp-report-only' => FALSE,

    'profiles' => array(
    	'global' => array(
    		'csp' => array(
    			'default-src' => "'none'",
    			'img-src' => "'self'",
    			'script-src' => "'self'",
    			'style-src' => "'self'",
    			'font-src' => "'self'", 
    			'object-src' => "'none'",
    			'media-src' => "'none'",
    			'frame-src' => "'none'",
    			'connect-src' => "'self'",
    			//'report-uri' => 'header-csp-report',
    			//'sandbox' => 'allow-forms allow-same-origin allow-scripts allow-top-navigation',
    		),
    		'custom' => array(
    			'x-frame-options' => 'DENY',
    			'x-content-type-options' => 'nosniff',
    			'x-xss-protection' => '1; mode=block'
    		)
    	),
    	'google' => array(
    		'csp' => array(
    			'script-src' => array(
    				"'unsafe-inline'",
    				"'unsafe-eval'",
    				'*.google.com',
    				'*.gstatic.com',
    				'*.google-analytics.com',
    				'stats.g.doubleclick.net'
    			),
    			'style-src' => array(
    				"'unsafe-inline'",
    				'*.googleapis.com'
    			),
    			'font-src' => array(
    				'*.gstatic.com',
    				'*.googleapis.com',
    				'data:'
    			),
    			'img-src' => array(
    				'*.google-analytics.com',
    				'data:'
    			),
    			'frame-src' => array(
    				'*.google.com'
    			),
    		)
    	),
    	'gravatar' => array(
    		'csp' => array(
    			'img-src' => array(
    				'*.gravatar.com'
    			)
    		)
    	),
    	'flickr' => array(
    		'csp' => array(
    			'img-src' => array(
    				'*.staticflickr.com'
    			)
    		)
    	)
    )
);