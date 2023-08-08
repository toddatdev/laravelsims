# Build the frontend
FROM node:15.14.0-stretch as frontendbuild
# RUN mkdir /tmp/sims
COPY . /app
WORKDIR /app

RUN npm install  && \
    npm install --save jszip && \
    npm install --save pdfmake && \
    npm install --save datatables.net-dt && \
    npm install --save datatables.net-buttons-dt && \
    npm install --save datatables.net-fixedheader-dt && \
    npm install --save datatables.net-keytable-dt && \
    npm install --save datatables.net-responsive-dt && \
    npm install --save datatables.net-rowgroup-dt && \
    npm install --save datatables.net-rowreorder-dt && \
    npm install --save datatables.net-select-dt && \
    npm install --save jquery && \
    npm install --save jquery-ui && \
    npm run dev
RUN cd /tmp && rm -rf ./node_modules

# Copy into php-fpm container for serving the app
FROM richarvey/nginx-php-fpm:1.10.3
WORKDIR /var/www/html
ENV APP_KEY=base64:vWcSvEfMgXJEEq0vn3XNwBbYnBikSKIJ6WnZrmegewk=
COPY --from=frontendbuild /app /var/www/html/

RUN cd /var/www/html && composer global require laravel/installer
RUN cd /var/www/html && composer install
RUN rm -rf /root/.composer

# Copy our startup scripts over
RUN mkdir /var/www/html/scripts
COPY .docker/scripts /var/www/html/scripts 

# Copy our nginx configs
RUN cd .docker/conf/nginx/ && \
    pip3 install pyaml && \
    python3 build_scp.py && \
    mv nginx-site.conf /etc/nginx/sites-available/default.conf

# Clean up our files
RUN rm -rf /var/www/html/.docker

# Update permissions
RUN chown -Rf nginx: /var/www/html