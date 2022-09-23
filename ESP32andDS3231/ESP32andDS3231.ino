#include <WiFi.h>
#include <time.h>              // Timeライブラリ
#include <DS3232RTC.h>         // DS3232、DS3231用ライブラリ
#include <esp_sntp.h>

DS3232RTC MaihaAlarmRTC(false);
// 2022/03/27：曜日の文字数を9文字固定に変更
const char* weekStr[7] = {"Sunday   ","Monday   ","Tuesday  ","Wednesday","Thursday ","Friday   ","Saturday "};
const char* ssid      = "********"; // Your SSID
const char* password  = "********"; // Your Password
const char* ntpServer = "ntp.nict.jp";
const long  gmtOffset_sec = 32400;
const int   daylightOffset_sec = 0;

void setup() {
  struct tm timeInfo;
  Serial.begin(115200);
  MaihaAlarmRTC.begin();
  //WiFi接続
  WiFi.begin(ssid, password);
  while(WiFi.status() != WL_CONNECTED) {
    Serial.print("."); // 進捗表示
    delay(500);
  }
  // WiFi接続の表示
  Serial.println("");
  Serial.println("WiFi connected");
  delay(2000);
  // NTPサーバからJST取得
  configTime(gmtOffset_sec, daylightOffset_sec, ntpServer);
  delay(2000);
  // 内蔵RTCの時刻がNTP時刻に合うまで待機
  while (sntp_get_sync_status() == SNTP_SYNC_STATUS_RESET) {
    Serial.print(">"); // 進捗表示
    delay(1000); 
  }
  //内蔵RTC時刻 = NTP時刻の表示
  Serial.println("Time matched");
  delay(2000);
  // 内蔵RTCの時刻の取得
  getLocalTime(&timeInfo);
  // 内蔵RTCの時刻をDS3231に時刻設定
  //setTime(12, 56, 0, 20, 8, 2022);  // 手動設定・動作確認用（時、分、秒、日、月、年）
  setTime(timeInfo.tm_hour, timeInfo.tm_min, timeInfo.tm_sec, timeInfo.tm_mday, timeInfo.tm_mon + 1, timeInfo.tm_year + 1900);
  MaihaAlarmRTC.set(now());
  //WiFi切断
  WiFi.disconnect(true);
  WiFi.mode(WIFI_OFF);
}

void loop(void) {
  showSerialMonitor();
  delay(1000); // 1秒ごとに時刻を取得する。1分ごとにする場合は60000と設定する
}

#define countof(a) (sizeof(a) / sizeof(a[0]))
void showSerialMonitor(void) {
  // RTCから時刻取得
  tmElements_t tm;
  MaihaAlarmRTC.read(tm);
  char datestring[20];
  snprintf_P(datestring, 
    countof(datestring),
    PSTR("%04u/%02u/%02u %02u:%02u:%02u"),
    tm.Year + 1970,
    tm.Month,
    tm.Day,
    tm.Hour,
    tm.Minute,
    tm.Second );
  Serial.println(datestring);
}
