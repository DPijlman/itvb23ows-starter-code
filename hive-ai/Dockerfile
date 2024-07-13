#FROM python:3.8
#RUN apt-get update && apt-get clean
#RUN pip install Flask==3.0.0
#WORKDIR /app
#COPY . /app
#EXPOSE 5000
#CMD ["flask", "--app", "app", "run", "--host", "0.0.0.0"]
# Use an official Python runtime as a parent image
#FROM python:3.9-slim

# Set the working directory
#WORKDIR /usr/src/app

# Copy the current directory contents into the container at /usr/src/app
#COPY . .

# Install any needed packages specified in requirements.txt
#RUN pip install --no-cache-dir -r requirements.txt

# Make port 5000 available to the world outside this container
#EXPOSE 5000

# Define environment variable
#ENV FLASK_APP=app.py

# Run app.py when the container launches
#CMD ["flask", "run", "--host=0.0.0.0"]

FROM python:3.10-alpine

COPY . .

RUN pip install -r requirements.txt

ENV FLASK_APP=app.py

EXPOSE 5000

CMD ["flask", "run", "--host", "0.0.0.0", "--port=5000", "--debug"]