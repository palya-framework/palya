Vagrant.configure("2") do |config|
    config.vm.box = "trusty-server-cloudimg-amd64-vagrant"
    config.vm.box_url = "https://cloud-images.ubuntu.com/vagrant/trusty/current/trusty-server-cloudimg-amd64-vagrant-disk1.box"

    config.vm.network "private_network", ip: "192.168.0.40"
    config.vm.synced_folder ".", "/home/vagrant/project", :nfs => true

    config.vm.provider "virtualbox" do |virtualbox|
        virtualbox.customize ["modifyvm", :id, "--memory", 1024]
        virtualbox.customize ["modifyvm", :id, "--cpus", 2]
    end

    config.vm.provision "shell", path: "build/script.sh"
end
