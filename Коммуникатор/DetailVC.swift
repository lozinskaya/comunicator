//
//  DetailVC.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 11.01.2021.
//

import UIKit

class DetailVC: UIViewController {

    var chooseCell: Array<String>?
    @IBOutlet weak var labelCell: UILabel!
    @IBOutlet weak var imgCell: UIImageView!
    @IBOutlet weak var timeCell: UILabel!
    @IBOutlet weak var personCell: UILabel!
    @IBOutlet weak var descriptionCell: UILabel!
    override func viewDidLoad() {
        super.viewDidLoad()

        labelCell.text = chooseCell?[0]
        imgCell.image = UIImage(named: "eventImg")
        descriptionCell.text = chooseCell?[1]
        timeCell.text = chooseCell?[2]
        personCell.text = chooseCell?[3]
    }
    

    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destination.
        // Pass the selected object to the new view controller.
    }
    */

}
