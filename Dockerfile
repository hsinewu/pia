# dockerRun: -v $(pwd):/var/pia -p 80:80 -it
FROM centos:6

ADD sys_setup /var/pia_setup
RUN sh /var/pia_setup/setup_cli.sh
RUN sh /var/pia_setup/setup_web_server.sh
RUN sh /var/pia_setup/setup_wkhtml2pdf.sh
RUN sh /var/pia_setup/setup_cli.sh

CMD /bin/bash
CMD ["sh","/var/pia/artisan-serve.sh"]
