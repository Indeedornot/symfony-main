FROM debian

RUN apt-get update
RUN apt-get -y install wget

ARG APP_ENV
# IF APP_ENV=prod THEN do not run
RUN if [ "$APP_DEBUG" = "0" ] ; then exit 1 ; fi

RUN wget -O /var/dbgpProxy https://xdebug.org/files/binaries/dbgpProxy
RUN chmod 0777 /var/dbgpProxy
RUN chmod +x /var/dbgpProxy

ARG DBGP_PROXY_PORT
ARG XDEBUG_CLIENT_PORT

CMD echo "DBGP_PROXY_URL: ${DBGP_PROXY_PORT}" && echo "XDEBUG_PORT: ${XDEBUG_CLIENT_PORT}" && /var/dbgpProxy -i 0.0.0.0:${DBGP_PROXY_PORT} -s 0.0.0.0:${XDEBUG_CLIENT_PORT}
