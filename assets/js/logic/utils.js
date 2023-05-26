export const isOk = rsp => {
  return (rsp && rsp.status == 200 && !rsp.data.error);
}

export const isError = rsp => {
  return (rsp && rsp.status == 200 && rsp.data.hasOwnProperty('error'));
}