FROM node:18.4.0-alpine3.16

WORKDIR /app

COPY ./frontend .

RUN npm install

CMD [ "npm", "run", "dev" ]