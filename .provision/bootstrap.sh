#!/usr/bin/env bash

#install needed packages
wget -qO- https://www.mongodb.org/static/pgp/server-3.6.asc | sudo apt-key add
sudo bash -c "echo deb http://repo.mongodb.org/apt/ubuntu xenial/mongodb-org/3.6 multiverse > /etc/apt/sources.list.d/mongodb-org.list"
apt-get update
apt-get install -y php-fpm
apt-get install -y nginx php php-mysql php-gd php-imagick php-xml mariadb-server mariadb-client mongodb-org
apt-get upgrade -y

service mongod start

#write out nginx config files
>/etc/nginx/sites-enabled/default
cat >> /etc/nginx/sites-enabled/vagrant << 'EOF'
upstream php-fpm {
        server unix:/var/run/php/php7.2-fpm.sock;
}

server {
	server_name __;
	root /vagrant/public;
	listen 80;
	index index.php;

	location = /favicon.ico {
		log_not_found off;
		access_log off;
	}

	location = /robots.txt {
		allow all;
		log_not_found off;
		access_log off;
	}
    
    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        fastcgi_pass php-fpm;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        internal;
    }

    # return 404 for all other php files not matching the front controller
    location ~ \.php$ {
        return 404;
    }
    

	location ~ /\. {
		deny all;
	}

	# Feed
	location ~* \.(?:rss|atom)$ {
		expires 1h;
		add_header Cache-Control "public";
	}

	# Media: images, icons, video, audio, HTC
	location ~* \.(?:jpg|jpeg|gif|png|ico|cur|gz|svg|svgz|mp4|ogg|ogv|webm|htc)$ {
		expires 1M;
		access_log off;
		add_header Cache-Control "public";
	}

	# CSS and Javascript
	location ~* \.(?:css|js|woff)$ {
		expires 1y;
		access_log off;
		add_header Cache-Control "public";
	}
}
EOF

systemctl reload nginx

#setup mariadb user account
mysql -e "USE mysql;"
mysql -e "CREATE USER 'vagrant'@'localhost' IDENTIFIED BY 'vagrant';"
mysql -e "GRANT ALL PRIVILEGES ON *.* TO 'vagrant'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

#configure wordpress installation dependencies

#setup database "wordpress"
mysql -e "CREATE DATABASE demo;"

systemctl restart php7.2-fpm
systemctl restart nginx

#last minute updates and upgrades
apt-get update
apt-get upgrade -y
