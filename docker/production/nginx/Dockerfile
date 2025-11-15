FROM nginx:alpine

COPY nginx.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www
EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
