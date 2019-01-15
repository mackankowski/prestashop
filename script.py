"""Hello Analytics Reporting API V4."""
import random

from googleapiclient.discovery import build
from oauth2client.service_account import ServiceAccountCredentials

SCOPES = ['https://www.googleapis.com/auth/analytics.readonly']
KEY_FILE_LOCATION = '/home/student/biznes/prestashop/biznes-227715-23e819d5819d.json'
VIEW_ID = '166549632'


def initialize_analyticsreporting():
    """Initializes an Analytics Reporting API V4 service object.

  Returns:
    An authorized Analytics Reporting API V4 service object.
  """
    credentials = ServiceAccountCredentials.from_json_keyfile_name(
        KEY_FILE_LOCATION, SCOPES)

    # Build the service object.
    analytics = build('analyticsreporting', 'v4', credentials=credentials)

    return analytics


def get_report(analytics):
    """Queries the Analytics Reporting API V4.

  Args:
    analytics: An authorized Analytics Reporting API V4 service object.
  Returns:
    The Analytics Reporting API V4 response.
  """
    return analytics.reports().batchGet(
        body={
            'reportRequests': [
                {
                    'viewId': VIEW_ID,
                    'dateRanges': [{'startDate': '7daysAgo', 'endDate': 'today'}],
                    'metrics': [{"expression": "ga:uniquePurchases"}],
                    'dimensions': [{'name': 'ga:productSku'}],
		    'orderBys': [{"fieldName": "ga:uniquePurchases", "sortOrder": "DESCENDING"}]
                }]
        }
    ).execute()


def print_response(response):
    """Parses and prints the Analytics Reporting API V4 response.

  Args:
    response: An Analytics Reporting API V4 response.
  """
    cv = []
    licznik = 0
    dim = 0
    for report in response.get('reports', []):
        columnHeader = report.get('columnHeader', {})
        dimensionHeaders = columnHeader.get('dimensions', [])
        metricHeaders = columnHeader.get('metricHeader', {}).get('metricHeaderEntries', [])

        for row in report.get('data', {}).get('rows', []):
            dimensions = row.get('dimensions', [])
            dateRangeValues = row.get('metrics', [])

            temp = [0, 0, 0]
            for header, dimension in zip(dimensionHeaders, dimensions):
                #print header + ': ' + dimension
                temp[0] = int(dimension)

            for i, values in enumerate(dateRangeValues):
                # print 'Date range: ' + str(i)
                licz = 1
                for metricHeader, value in zip(metricHeaders, values.get('values')):
                    #print metricHeader.get('name') + ': ' + value
                    temp[licz] = int(value)
                    licz += 1
            cv.append(temp)
            licznik += 1
    productCv(cv)


def productCv(cv):
  licz = 0
  cvTable = []
  for x in cv:
    productId = x[0]
    if licz <5:
	cvTable.append(productId)
    licz+=1

  f = open("/home/student/biznes/recommender-system/target/popular.csv", "w")
  for x in cvTable:
    f.write(str(x) + "\n")
  f.close()

def main():
    analytics = initialize_analyticsreporting()
    response = get_report(analytics)
    print_response(response)


if __name__ == '__main__':
    main()
