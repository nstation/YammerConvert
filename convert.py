import csv
import glob
import datetime

CHANNEL_NAME = 'チャンネル' # インポートするチャンネル名
IMPORT_DIR = 'Import'		# 入力用ディレクトリ
EXPORT_DIR = 'Export'		# 出力用ディレクトリ
MAIL_POS = 13
BODY_POS = 14
TIME_POS = 20

file_list = glob.glob(f'.\{IMPORT_DIR}\*.csv')
print('File: '+str(len(file_list)))

for file_name in file_list :
	csv_data = ''
	with open(file_name, 'r', encoding='utf-8') as f:
		i = 0
		csv_reader = csv.reader(f)
		for value in csv_reader:
			if i == 1:	# メールアドレスを使用して出力ファイル名の設定
				name_num = value[MAIL_POS].find('@')
				name = value[MAIL_POS][0:name_num]
				w = f'.\{EXPORT_DIR}\{name}.csv'
			if i != 0:	# 最初の行(ヘッダー)以外を処理
				ut = int((datetime.datetime.strptime(value[TIME_POS],"%Y-%m-%dT%H:%M:%S.%fZ")).timestamp())
				csv_data += f'"{ut}","{CHANNEL_NAME}",{name}","{value[BODY_POS]}"\n'
			i += 1
		with open(w, 'w', encoding='utf-8', newline='\n') as wf:
			wf.write(csv_data)