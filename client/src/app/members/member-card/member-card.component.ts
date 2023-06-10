import { Component, Input, ViewChild } from '@angular/core';
import { ToastrService } from 'ngx-toastr';
import { Member } from 'src/app/_models/member';
import { MembersService } from 'src/app/_services/members.service';
import { PresenceService } from 'src/app/_services/presence.service';

@Component({
  selector: 'app-member-card',
  templateUrl: './member-card.component.html',
  styleUrls: ['./member-card.component.scss']
})
export class MemberCardComponent {
  @Input() member: Member | undefined;
  preventLike = false;

  constructor(private membersService: MembersService, private toastr: ToastrService) {

  }

  addLike(member: Member) {
    this.preventLike = true;
    this.membersService.addLike(member.userName).subscribe({
      next: () => {
        this.toastr.success('You have liked ' + member.knownAs)
        member.likeStatus = 'liked';
        this.preventLike = false;
      }, complete: () => {
        this.preventLike = false;
      }
    })
  }

  unlike(member: Member) {
    this.preventLike = true;
    this.membersService.unLike(member.userName).subscribe({
      next: () => {
        this.toastr.success('You have unliked ' + member.knownAs)
        member.likeStatus = 'none';
      },
      complete: () => {
        this.preventLike = false;
      }
    })
  }
}


