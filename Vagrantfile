Vagrant.configure("2") do |config|
    config.vm.box = "trusty-server-cloudimg-amd64-vagrant"
    config.vm.box_url = "https://cloud-images.ubuntu.com/vagrant/trusty/current/trusty-server-cloudimg-amd64-vagrant-disk1.box"

    config.vm.network "private_network", ip: "192.168.0.13"
    config.vm.network :forwarded_port, guest:8200, host:8200

    config.vm.synced_folder ".", "/vagrant", type: "nfs"

    config.vm.provider "virtualbox" do |virtualbox|
        virtualbox.customize ["modifyvm", :id, "--memory", 1024]
        virtualbox.customize ["modifyvm", :id, "--cpus", 2]
    end

    config.vm.provision :puppet do |puppet|
        puppet.manifest_file = "palya.pp"
        puppet.manifests_path = "puppet/manifests"
        puppet.module_path = "puppet/modules"
    end
end
