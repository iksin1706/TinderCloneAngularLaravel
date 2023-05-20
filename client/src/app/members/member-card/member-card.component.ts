import { Component, Input } from '@angular/core';
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
  @Input() member: Member | undefined


  constructor(private membersService: MembersService, private toastr: ToastrService){

    }
  
  addLike(member: Member){
    this.membersService.addLike(member.userName).subscribe({
      next: () => this.toastr.success('You have liked ' +  member.knownAs)
    })
  }
}


