
from build_report_controller import *
from build_report_model import *
from build_report_view import *
from build_config import *
from tornado.template import Template
import json


if __name__ == '__main__':
    sys.argv = ['build_report.py', '--command=build_report', '--table=kx_user', '--prefix=', '--config=D:\\Projects\\AdminBuildr\\config\\config.json']
    config = load_config()

    print(config)

    model_file = build_report_model_by_config(config)
    view_files = build_report_view_by_config(config)
    controller_file = build_report_controller_by_config(config)

    d = [model_file, controller_file] + view_files

    print(json.dumps(d))

