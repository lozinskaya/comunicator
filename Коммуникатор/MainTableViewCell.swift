//
//  MainTableViewCell.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 11.01.2021.
//

import UIKit

class MainTableViewCell: UITableViewCell {

    @IBOutlet weak var titleEvent: UILabel!
    @IBOutlet weak var descriptionEvent: UILabel!
    @IBOutlet weak var imgEvent: UIImageView!
    @IBOutlet weak var timeEvent: UILabel!
    @IBOutlet weak var countPersonsEvent: UILabel!
    override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)

        // Configure the view for the selected state
    }
    
}
