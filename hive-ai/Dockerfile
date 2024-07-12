FROM python:3.8

RUN apt-get update && apt-get clean

RUN pip install Flask==3.0.0

WORKDIR /app

COPY . /app

EXPOSE 5000

CMD ["flask", "--app", "app", "run", "--host", "0.0.0.0"]