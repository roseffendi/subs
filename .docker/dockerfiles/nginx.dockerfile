#
# Build nginx image
#

# Define base image
FROM nginx:1.23-alpine

# Copy nginx configuration
COPY ./.docker/config/backend.conf /etc/nginx/conf.d/backend.conf

RUN mkdir -p /var/www/public
RUN touch /var/www/public/index.php