FROM dustinscarberry/symfony:php8.5

# set workdir
WORKDIR /var/www/html

# copy app files to /var/www
COPY --chown=www-data:www-data . /var/www/html

# build app dependencies
RUN apk upgrade && apk add --no-cache yarn && \
  composer i --no-scripts && \
  yarn install --immutable && \
  yarn prod && \
  apk del yarn

EXPOSE 80
