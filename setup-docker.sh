sudo yum remove -y docker \
                  docker-client \
                  docker-client-latest \
                  docker-common \
                  docker-latest \
                  docker-latest-logrotate \
                  docker-logrotate \
                  docker-selinux \
                  docker-engine-selinux \
                  docker-engine

sudo yum install -y yum-utils \
  device-mapper-persistent-data \
  lvm2

sudo yum-config-manager \
    --add-repo \
    https://download.docker.com/linux/centos/docker-ce.repo

sudo yum install -y docker-ce

sudo systemctl enable docker

sudo groupadd docker

sudo usermod -aG docker $USER

# postpone docker boot time initiations
# echo manual | sudo tee /etc/init/docker.override

# open the xdebug incoming port to the host of the PHPStorm IDE system
sudo iptables -I INPUT -p tcp --dport 9000 -j ACCEPT
# add it in the permanent rules
sudo iptables-save

sudo systemctl start docker
# this will remind the user to create a docker.io account and use it
docker login

