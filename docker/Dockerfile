FROM dustinscarberry/symfony-base:php8.0.13

# set workdir
WORKDIR /var/www/html

# copy app files to /var/www
COPY --chown=www-data:www-data . /var/www/html

# build app dependencies
RUN apk add --no-cache make gcc g++ python3 npm && \
  composer i --no-scripts --no-dev && \
  npm ci && \
  npm run prod && \
  apk del make gcc g++ python3 npm && \
  rm -rf node_modules

# fix web permissions
RUN chown -R www-data:www-data *

EXPOSE 80
