class ApiConfig {
  static String baseUrl = 'http://10.0.2.2:8000/api';

  static void updateBaseUrl(String newUrl) {
    baseUrl = newUrl;
  }
}