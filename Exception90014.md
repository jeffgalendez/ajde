# Exception 90014 #
## Directory _DIRECTORYNAME_ is not writable ##

# Details #

Some classes need to have file access to certain directories. Preferably, the `private/shared` directory is used for this purpose.

# Solution #

In Linux, `chmod` the designated directory using for example:
```
user@linux:~/ajde$ chmod 0777 private/shared/cache/
```