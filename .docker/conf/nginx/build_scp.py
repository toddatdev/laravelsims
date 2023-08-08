import yaml
fullstring = "add_header Content-Security-Policy \""
i = 1
with open(r'csp.yml') as file:
    header = yaml.load(file, Loader=yaml.FullLoader)
    length = len(header)
    for item in header:
        fullstring = fullstring + item + " "
        for line in header[item]:
            fullstring = fullstring + line + " "
        if i != length:
            fullstring = fullstring + "; "
        i += 1
fullstring = fullstring + "\" always;"


template = open("nginx-site.conf.tpl", "rt")

config = open("nginx-site.conf", "wt")

for line in template:
    config.write(line.replace('csp_header', fullstring))

template.close()
config.close()