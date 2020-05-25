 # This will build the docker container from the Dockerfile in this directory
 docker build -t cse-341-w05-prove .

 # This will run the docker container that was built and make it available at port 8181; localhost:8181
 docker run -p 8181:80 -d cse-341-w05-prove