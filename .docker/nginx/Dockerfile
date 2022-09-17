From nginx:1-alpine

RUN apk --update add alpine-conf && \
    setup-timezone -z Asia/Tokyo && \
    apk del alpine-conf && \
    rm -rf /var/cache/apk/*

RUN adduser -S www-data -u 82 \
    && echo "www-data ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers \
    && echo 'www-data:www-data' | chpasswd

EXPOSE 80

VOLUME [ "/app" ]
