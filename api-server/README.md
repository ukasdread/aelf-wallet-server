# AELF Wallet management system

The system provides data service, announcement management, user statistics and system management functions for aelf app


### development environment

- Linux Ubuntu 16.04.6 LTS
- PHP 7.3.6
- Mysql 5.6.16
- Nginx 1.10.3

### Deployment steps

1.Nginx Partial configuration
```nginx
location / {
            #root   html;
            index  index.html index.htm index.php;
            if (!-e $request_filename){
                 rewrite ^/(.*)$ /index.php/$1 last;
                 #break;
            }
        }
```

2.Modify profile data/config.inc.php
3.AliYun OSS configuration information
```php
//path core/v2.1/Aliyun_OSS.php
private static function instance() {
		if ( ! isset( self::$inst ) ) {
			self::$inst = new self();
            self::$inst->keyID = '';
            self::$inst->keySecret = '';
			self::$inst->endPoint = '';
			self::$inst->ossClient = new OssClient( self::$inst->keyID, self::$inst->keySecret, self::$inst->endPoint );
		}
		return self::$inst;
	}
```
3.Import sql
```angular2
data/aelf_test.sql
```
4.Modify database profile

aelf Interface file configuration
```json
//#table cc_config_data-->api_config
{
    "web_api": {
        "AELF": "",  //aelf chain node（agent）
        "tDVV": ""   //tdvv chain node（agent）
    },
    "balance_url": "http://127.0.0.1:8000/elf",
    "base58_url": "http://127.0.0.1:8000/elf_trans",
    "address_url": "http://127.0.0.1:8000/elf_address",
    "tokens_url": "http://127.0.0.1:8001/elf_tokens",
    "history_api": {
        "AELF": "", //aelf scaner chain api
	    "tDVV": "" //tdvv scaner chain api
    },
    "scaner_node": {
        "AELF": "", //aelf chain node
        "tDVV": ""  //tdvv chain node
    },
    "chain_color": {
        "AELF": "#5C28A9",
        "tDVV": "#4B60DD"
    },
    "base58_nodes": {
        "AELF": "9992731",
        "tDVV": "1866392"
    }
}
```
CrossChain information
```json
//#table cc_config_data-->chains
[
    {
        "type": "main",
        "name": "AELF",
        //contract address
        "contract_address": "25CecrU94dmMdbhC3LWMKxtoaL4Wv8PChGvVJM6PxkHAyvXEhB",
        "node": "",
        "symbol": "ELF",
        "logo": "elf_wallet/elf/elf.png",
        "explorer": "https://explorer-test.aelf.io",
        //crosschain contract address
        "crossChainContractAddress": "x7G7VYqqeVAH8aeAsb7gYuTQ12YS1zKuxur9YES3cUj72QMxJ",
        "transferCoins": "ELF"
    },
    {
        "type": "side",
        "name": "tDVV",
        //contract address
        "contract_address": "EReNnYPBeZ3AfAjPXXdpNK7AV5YCjRPvM7d5M3SLettMZpxre",
        "node": "",
        "symbol": "ELF",
        "logo": "elf_wallet/elf/tDVV.png",
        "explorer": "https://explorer-test-side01.aelf.io",
        //crosschain contract address
        "crossChainContractAddress": "RSr6bPc7Hv6dMJiWdPgBBFMacUJcrgQoeHkVBMjqJ5HURtKK3",
        "transferCoins": "ELF"
    }
]

```

### login 
```
 url http://ip:port/index.php?con=admin&ctl=default
 admin  super_admin
 passwd Admin@123
```

