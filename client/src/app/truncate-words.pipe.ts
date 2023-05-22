import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'truncateWords'
})
export class TruncateWordsPipe implements PipeTransform {
  transform(value: string, limit: number): string {
    if (value.length <= limit) {
      return value;
    }

    const words = value.substr(0, limit).split(' ');

    words.pop();

    return words.join(' ') + '...';
  }
}
