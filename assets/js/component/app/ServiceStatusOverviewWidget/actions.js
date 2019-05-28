export const isValidResponse = (rsp) =>
{
  return rsp && rsp.status == 200 && !rsp.data.error;
}
