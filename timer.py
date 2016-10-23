import _thread
import datetime
import time
import sys
import os


period_time = 1
command     = sys.argv[1]


def execute(thread_name, delay):

	while True:

		time.sleep(delay)
		os.system(command)

		date = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
		print("[" + thread_name + "] [" + str(date) + "]")


try:

	print("Timer Started !")
	_thread.start_new_thread(execute, ("Timer", period_time))

except Exception as e:
	print(str(e))

while True:
	pass