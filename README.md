# auto-wordpress
Install and create a Wordpress blog running on the Apache webserver, complete with a weather auto-post.

## Notes:
* The playbooks were designed to run only one instance at a time. Be sure to tear down your provisioned instance when finished.

## Setup
The playbooks assume that AWS credentials are already set as environmental variables. 

```
    export AWS_ACCESS_KEY_ID='your_access_key_id'
    export AWS_SECRET_ACCESS_KEY='your_secret_access_key'
```


Build the docker image for the ansible container. 

```
    docker build -t ansible .
```


Launch the ansible container and enter the bash shell, where the playbooks will be run.

```
    . run
```


## Playbooks
Provision the EC2 instance. This will also create the security group for the instance.
To specify inbound/outbound traffic, the instance's security group is not set in a variable.

```
    ansible-playbook -i hosts launch_ec2.yml
```


To install and run Wordpress, the `ec2.py` dynamic inventory file will be used; because 
an EIP is not used, this file will effectively target the single instance which was provisioned. 
If the user already owns a domain name and would instead like to set the name as the custom url,
use `-e "wp_home_site=some_site.com"` within the command.

```
   ansible-playbook -i ec2.py site.yml
```
 
 
 The Wordpress blog should now be running. In your web browser, type the address of the EC2 instance to see
 the blog running on the webserver, complete with an automatically-generated post which states the weather
 (the location can be set in the `weather.php` file).


Once finished, tear down the instance with the following command: 

```
    ansible-playbook -i hosts terminate_ec2.yml
```


Exit out of the bash shell and return to your local machine.

```
    exit
```
