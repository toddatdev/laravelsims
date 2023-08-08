
proxy_buffering off;
add_header X-Content-Type-Options nosniff;
add_header X-XSS-Protection "1; mode=block";
proxy_cookie_path / "/; HTTPOnly; Secure";
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
add_header X-Frame-Options "SAMEORIGIN";
csp_header


server {
	listen   80; ## listen for ipv4; this line is default and implied
	listen   [::]:80 default ipv6only=on; ## listen for ipv6

	root /var/www/html/public;
	index index.php index.html index.htm;
	
	# Disable sendfile as per https://docs.vagrantup.com/v2/synced-folders/virtualbox.html
	sendfile off;
    
	# Increase the file upload size
	client_max_body_size 500M;

	# Add stdout logging
	error_log /dev/stdout info;
	access_log /dev/stdout;

        # Add option for x-forward-for (real ip when behind elb)
        #real_ip_header X-Forwarded-For;
        #set_real_ip_from 172.16.0.0/12;

	# block access to sensitive information about git
	location /.git {
		   deny all;
           return 403;
        }

	location / {
		# First attempt to serve request as file, then
		# as directory, then fall back to index.html
		try_files $uri $uri/ /index.php?$query_string;
	}

	error_page 404 /404.html;
        location = /404.html {
                root /var/www/errors;
                internal;
        }

        location ^~ /sad.svg {
            alias /var/www/errors/sad.svg;
            access_log off;
        }
        location ^~ /twitter.svg {
            alias /var/www/errors/twitter.svg;
            access_log off;
        }
        location ^~ /gitlab.svg {
            alias /var/www/errors/gitlab.svg;
            access_log off;
        }

	# pass the PHP scripts to FastCGI server listening on socket
	#
	location ~ \.php$ {
        try_files $uri =404;
		fastcgi_split_path_info ^(.+\.php)(/.+)$;
		fastcgi_pass unix:/var/run/php-fpm.sock;
		fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    		fastcgi_param SCRIPT_NAME $fastcgi_script_name;
		fastcgi_index index.php;
		include fastcgi_params;
	}

        location ~* \.(jpg|jpeg|gif|png|css|js|ico|webp|tiff|ttf|svg)$ {
                expires           5d;
        }

	# deny access to . files, for security
	#
	location ~ /\. {
    		log_not_found off; 
    		deny all;
	}
        
	location ^~ /.well-known {
                allow all;
                auth_basic off;
        }

}
