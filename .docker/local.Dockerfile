FROM richarvey/nginx-php-fpm:1.10.3
WORKDIR /var/www/html
ENV APP_KEY=base64:vWcSvEfMgXJEEq0vn3XNwBbYnBikSKIJ6WnZrmegewk=

# Copy our startup scripts over
RUN mkdir /var/www/html/scripts
COPY .docker/scripts /var/www/html/scripts

COPY .docker/conf/nginx /var/www/html/.docker/conf/nginx

RUN cd .docker/conf/nginx/ && \
    pip3 install pyaml && \
    python3 build_scp.py && \
    mv nginx-site.conf /etc/nginx/sites-available/default.conf
